import { LATagSuggest } from "./modules/LATagSuggest.js";
import * as LAvx from "./modules/LAvx.js";
import * as LArenderor from "./modules/LArenderor.js";

var postsearch = {};

window.addEventListener('DOMContentLoaded', () => 
{
    postsearch.setVars();
    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.formEle = document.querySelector('#postsearch');
    postsearch.categoryEle = document.querySelector('#postsearch_category');
    postsearch.tagsParentEle = document.querySelector('#postsearch_tags_container');
    postsearch.tagsEle = document.querySelector('#postsearch_tags');
    postsearch.tagSuggestionsEle = document.querySelector('#postsearch_tag_suggestions');
    postsearch.searchControlsContainerEle = document.querySelector('#postsearch_controls');
    postsearch.searchFiltersContainerEle = document.querySelector('#postsearch_filters');
    postsearch.searchResultsContainerEle = document.querySelector('#postsearch_results');
}

postsearch.bindEvents = () => 
{
    postsearch.formEle.addEventListener('submit', (event) => {
        event.preventDefault();
        postsearch.submitSearch('form');
    });

    postsearch.initFromUrlPxs();

    LATagSuggest(postsearch.tagsParentEle, postsearch.tagsEle, postsearch.tagSuggestionsEle);
}

postsearch.initFromUrlPxs = () => {

    const urlPxs = new URLSearchParams(window.location.search);
    const pxPostCategory = urlPxs.get('post_category');
    const pxPostTags = urlPxs.get('post_tags');

    if (pxPostCategory) {
        postsearch.categoryEle.value = pxPostCategory;
        postsearch.submitSearch('form');
    }
    else if (pxPostTags) {
        postsearch.tagsEle.value = pxPostTags;
        postsearch.submitSearch('form');
    }

}

postsearch.submitSearch = (submitType) => {

    LArenderor.renderAjaxSpinner();

    // Get the post search pxs.
    const postPxs = postsearch.normalizeFilterData(submitType, 'filters');

    // Validate the search pxs are of a valid value - allow all nulls.
    LArenderor.clearError();
    if (submitType === 'form') {
        for (const[px, pxValue] of Object.entries(postPxs)) {
            if (pxValue !== null && !LAvx.vxText(pxValue, 64)) {
                LArenderor.renderError({
                    errorKey: px, 
                    errorValue: pxValue
                });
                return;
            }
        }
    }

    // Fetch the posts.
    return fetch(
        '/api/postsearch',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(postPxs)
        }
    )
    .then(response => response.json())
    .then(searchResults => {
        postsearch.renderSearch(submitType, searchResults);
    })
    .catch(error => {
        console.error('Error: ', error);
    })
    .finally(() => {
        LArenderor.unrenderAjaxSpinner();
    });
}

postsearch.normalizeFilterData = (submitType, dataType) => {

    const DEFAULT_CATEGORY_VALUE = 'Select Category';
    const DEFAULT_CATEGORY_CAPTION = 'All Categories';
    const DEFAULT_TAGS_CAPTION = 'All Tags';

    let categoryFilterTrimmed = null;
    let categoryFilter = null;
    let tagsFilterTrimmed = null;
    let tagsFilter = null;
    let categoryCaption = null;
    let tagsCaption = null;

    //
    switch (submitType) {

        case 'form':  
            categoryFilterTrimmed = postsearch.categoryEle.value.trim();
            categoryFilter = categoryFilterTrimmed === DEFAULT_CATEGORY_VALUE ? null : categoryFilterTrimmed;
            categoryCaption = categoryFilterTrimmed === DEFAULT_CATEGORY_VALUE ? DEFAULT_CATEGORY_CAPTION : categoryFilter;

            tagsFilterTrimmed = postsearch.tagsEle.value.trim().replace(/,*$/, '');
            tagsFilter = (tagsFilterTrimmed.length < 1) ? null : tagsFilterTrimmed;
            tagsCaption = tagsFilter === null ? DEFAULT_TAGS_CAPTION : tagsFilter;
        break;
        
        case 'dechip':
            const categoryFilterEle = document.querySelector('[data-type="post-category"]');
            const categoryFilterValue = categoryFilterEle?.getAttribute('data-value');
            categoryFilter = (categoryFilterValue && categoryFilterValue !== DEFAULT_CATEGORY_CAPTION) ? categoryFilterValue : null;
            categoryCaption = (categoryFilter !== null) ? categoryFilter : DEFAULT_CATEGORY_CAPTION;

            //
            const tagsFilterEle = document.querySelectorAll('[data-type="post-tag"]');
            if (tagsFilterEle.length > 0) {
                tagsFilter = '';
                tagsFilterEle.forEach(tag => {
                    tagsFilter += tag.getAttribute('data-value') + ', ';
                })
                tagsFilter = tagsFilter.trim().replace(/(,\s*)+$/, '');
                tagsFilter = (tagsFilter !== DEFAULT_TAGS_CAPTION) ? tagsFilter : null;
            }
            else {
                tagsFilter = null;
            }
            tagsCaption = (tagsFilter === null) ? DEFAULT_TAGS_CAPTION : tagsFilter;

        break;
    }

    const filters = {
        'postCategory': categoryFilter,
        'postTags': tagsFilter
    };
    
    const captions = {
        'postCategory': categoryCaption,
        'postTags': tagsCaption
    };

    return dataType === 'filters' ? filters : captions;

}


postsearch.renderSearch = (submitType, searchResults) => {

    //
    postsearch.renderSearchFilters(submitType);
    postsearch.renderSearchResults(searchResults);
}

postsearch.renderSearchResults = (searchResults) => {

    //
    postsearch.searchResultsContainerEle.innerHTML = '';

    //
    searchResults.forEach(searchResult => {
        postsearch.renderCard(searchResult);
    });
}


postsearch.renderSearchFilters = (submitType) => {

    const normalizedCaptionData = postsearch.normalizeFilterData(submitType, 'captions');
    postsearch.searchFiltersContainerEle.innerHTML = '';


    const tagCaptionData = normalizedCaptionData.postTags
        .split(',')
        .map(tag => tag.trim())
        .filter(tag => tag !== '')
        .map(tag =>
            `<span class="search-results-chip" data-type="post-tag" data-value="${tag}">
            ${tag}
            <button class="search-results-btn" aria-label="Remove">x</button>
            </span>
        `)
        .join('\n');

    const filters = document.createElement('div');
    filters.classList.add('site-gradient-border-top');
    filters.style.paddingTop = '1rem';

    filters.innerHTML = `
        <h2 class="search-results-heading">Showing results for:</h2>
        <div class="postsearch-filter-chips">

        <p class="search-results-px">
            <span class="search-results-label">Post Category -></span>
            <span class="search-results-chips">
                <span class="search-results-chip" data-type="post-category" data-value="${normalizedCaptionData.postCategory}">
                    ${normalizedCaptionData.postCategory}
                    <button class="search-results-btn" aria-label="Remove">x</button>
                </span>
            </span>
        </p>

        <p class="search-results-px">
            <span class="search-results-label">Post Tags -></span>
            <span class="search-results-chips">
                	${tagCaptionData}
            </span>				
        </p>        
        
        </div><!-- End postsearch_filters_chips -->
    `;

    postsearch.searchFiltersContainerEle.appendChild(filters);
    postsearch.bindChipRemovalEvent();

}

postsearch.bindChipRemovalEvent = () => {

    const chipEles = document.querySelectorAll('.search-results-btn');
    chipEles.forEach(btn =>
        btn.addEventListener('click', (event) => {
            const chipEle = event.target.closest('.search-results-chip');
                postsearch.removeChipFilter(chipEle);
                postsearch.submitSearch('dechip');
        })
    );

}

postsearch.removeChipFilter = (chipEle) => {

    const chipDataType = chipEle.getAttribute('data-type');

    switch(chipDataType) {

        case 'post-category':
            postsearch.categoryEle.value = 'Select Category';
        break;

        case 'post-tag':

            const tagValue = chipEle.getAttribute('data-value');
            const filteredTagValues = postsearch.tagsEle.value
            .split(',')
            .map(tag => tag.trim())
            .filter(tag => tag !== tagValue);

            postsearch.tagsEle.value = filteredTagValues.join(', ');

        break;
    }

    chipEle.remove();
}


postsearch.normalizeCardData = (cardData) => {
    return {
        post_name: cardData.post_name,
        post_category: cardData.post_category,
        post_created: postsearch.normalizeCardDate(cardData.post_created),
        short_desc: cardData.short_desc,
        long_desc: cardData.long_desc,
        intro: cardData.intro,
        search_img: cardData.search_img
    };
}

postsearch.normalizeCardDate = (cardDate) => {

    //
    const jsDate = new Date(cardDate);
    const normalizedCardDate = jsDate.toLocaleDateString('en-US',{
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    return normalizedCardDate;
}

postsearch.renderCard = (cardData) => {

    //
    const normalizedCardData = postsearch.normalizeCardData(cardData);
    const card = document.createElement('div');
    card.classList.add('site-box-shadow', 'search-card');

    //
    card.innerHTML = `
        <h2 class="search-card-desc">${normalizedCardData.short_desc}</h2>
        <div class="search-card-components">
            <a class="search-card-img" href="${encodeURIComponent(normalizedCardData.post_name)}">
                <img src="assets/${normalizedCardData.search_img}">
            </a>
            <a class="search-card-longdesc" href="${encodeURIComponent(normalizedCardData.post_name)}">
                ${normalizedCardData.long_desc}
            </a>
            <a class="search-card-intro" href="${encodeURIComponent(normalizedCardData.post_name)}">
                ${normalizedCardData.intro}
            </a>
        </div>
        <div class="search-card-attrs">
            <span class="search-card-date">${normalizedCardData.post_created}</span>
            <a class="search-card-cat" href="postsearch?post_category=${encodeURIComponent(normalizedCardData.post_category)}">
                ${normalizedCardData.post_category}
            </a>
        </div>
        `.trim();
    postsearch.searchResultsContainerEle.appendChild(card);
}