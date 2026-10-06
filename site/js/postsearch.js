import { LATagSuggest } from "./modules/LATagSuggest.js";
import * as LAvx from "./modules/LAvx.js";
import * as LArenderor from "./modules/LArenderor.js";

var postsearch = {};

window.addEventListener('DOMContentLoaded', () => 
{
    postsearch.setVars();

    // If there is no form ele, return.
    if (!postsearch.formEle) {
        return;
    }

    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.formEle = document.querySelector('#postsearch');
    postsearch.categoryEle = document.querySelector('#postsearch_category');
    postsearch.tagsParentEle = document.querySelector('#postsearch_controls');
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

    postsearch.initSearch();

    LATagSuggest(postsearch.tagsParentEle, postsearch.tagsEle, postsearch.tagSuggestionsEle);
}

postsearch.initSearch = () => {

    const urlPxs = new URLSearchParams(window.location.search);
    const pxPostCategory = urlPxs.get('post_category');
    const pxPostTags = urlPxs.get('post_tags');

    // No pxs sent - page directly navigated to.
    if (!pxPostCategory && !pxPostTags) {
        return;
    }

    // Populate postsearch controls.
    if (pxPostCategory) {
        postsearch.categoryEle.value = pxPostCategory;
    }

    if (pxPostTags) {
        postsearch.tagsEle.value = pxPostTags;
    }

    postsearch.submitSearch('form');
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
                LArenderor.unrenderAjaxSpinner();
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
        // TODO: Add a renderErrorPage to LArenderor class.
        console.error('Error: ', error);
    })
    .finally(() => {
        LArenderor.unrenderAjaxSpinner();
    });
}

postsearch.normalizeFilterData = (submitType, dataType) => {

    // Filter defaults when no input params provided.
    const DEFAULT_CATEGORY_VALUE = 'Select Category';
    const DEFAULT_CATEGORY_CAPTION = 'All Categories';
    const DEFAULT_TAGS_CAPTION = 'All Tags';

    let categoryFilterTrimmed = null;
    let categoryFilter = null;
    let tagsFilterTrimmed = null;
    let tagsFilter = null;
    let categoryCaption = null;
    let tagsCaption = null;

    // Determine if triggering action is form vs. filter chip removal.
    switch (submitType) {

        case 'form':  
            // Get the category value and caption.
            categoryFilterTrimmed = postsearch.categoryEle.value.trim();
            categoryFilter = categoryFilterTrimmed === DEFAULT_CATEGORY_VALUE ? null : categoryFilterTrimmed;
            categoryCaption = categoryFilterTrimmed === DEFAULT_CATEGORY_VALUE ? DEFAULT_CATEGORY_CAPTION : categoryFilter;

            // Get the tags value and caption.
            tagsFilterTrimmed = postsearch.tagsEle.value.trim().replace(/,*$/, '');
            tagsFilter = (tagsFilterTrimmed.length < 1) ? null : tagsFilterTrimmed;
            tagsCaption = tagsFilter === null ? DEFAULT_TAGS_CAPTION : tagsFilter;
        break;
        
        case 'dechip':
            // Set cat value and filter - don't crash if (the '?' optional chain in cat filter)
            // cat value is null or undefined.
            const categoryFilterEle = document.querySelector('[data-type="post-category"]');
            const categoryFilterValue = categoryFilterEle?.getAttribute('data-value');
            categoryFilter = (categoryFilterValue && categoryFilterValue !== DEFAULT_CATEGORY_CAPTION) ? categoryFilterValue : null;
            categoryCaption = (categoryFilter !== null) ? categoryFilter : DEFAULT_CATEGORY_CAPTION;

            // Set tag value and filter - remove leading/trailing spaces and trailing ', '
            // from value.
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

    // Return either the filters or captions.
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

    // Render filters and cards then apply searched state formatting.
    postsearch.renderSearchFilters(submitType);
    postsearch.renderSearchResults(searchResults);
    postsearch.renderSearchFormatting();
}

postsearch.renderSearchResults = (searchResults) => {

    //
    postsearch.searchResultsContainerEle.innerHTML = '';
    postsearch.searchResultsContainerEle.classList.add('postsearch-results-cards');

    //
    searchResults.forEach(searchResult => {
        postsearch.renderCard(searchResult);
    });
}


postsearch.renderSearchFilters = (submitType) => {

    //
    const normalizedCaptionData = postsearch.normalizeFilterData(submitType, 'captions');

    //
    postsearch.searchFiltersContainerEle.innerHTML = '';

    //
    const tagCaptionData = normalizedCaptionData.postTags
        .split(',')
        .map(tag => tag.trim())
        .filter(tag => tag !== '')
        .map(tag =>
            `<span class="postsearch-filters-chip site-font-std" data-type="post-tag" data-value="${tag}">
            ${tag}
            <button class="postsearch-filters-btn" aria-label="Remove">x</button>
            </span>
        `)
        .join('\n');

    //
    const filters = document.createElement('div');
    filters.classList.add('site-gradient-border-top');
    filters.style.paddingTop = '1rem';

    //
    filters.innerHTML = `
        <h2 class="postsearch-filters-header site-font-secondary-heading">Showing results for:</h2>
        <div class="postsearch-filters-list">

        <p class="postsearch-filters-cat-label">
            <span class="postsearch-filters-label site-font-std">Post Category -></span>
        </p>
        <p class="postsearch-filters-cat-filter">
            <span class="postsearch-filters-chip site-font-std" data-type="post-category" data-value="${normalizedCaptionData.postCategory}">
                ${normalizedCaptionData.postCategory}
                <button class="postsearch-filters-btn" aria-label="Remove">x</button>
            </span>
        </p>

        <p class="postsearch-filters-tags-label">
            <span class="postsearch-filters-label site-font-std">Post Tags -></span>
        </p>
        <p class="postsearch-filters-tags-filter">
            ${tagCaptionData}			
        </p>        
        
        </div>
    `;

    //
    postsearch.searchFiltersContainerEle.appendChild(filters);
    postsearch.bindChipRemovalEvent();

}

postsearch.bindChipRemovalEvent = () => {

    const chipEles = document.querySelectorAll('.postsearch-filters-btn');
    chipEles.forEach(btn =>
        btn.addEventListener('click', (event) => {
            const chipEle = event.target.closest('.postsearch-filters-chip');
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

    // Convert raw API card data into the attr format required by card rendering.
    return {
        post_name: cardData.post_name,
        post_category: cardData.post_category,
        post_created: postsearch.normalizeCardDate(cardData.post_created),
        short_desc: cardData.short_desc,
        long_desc: cardData.long_desc,
        intro: cardData.intro,
        post_img: cardData.post_img,
        img_html: cardData.img_html
    };
}

postsearch.normalizeCardDate = (cardDate) => {

    // Get a date of structure: January 1, 2006.
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

    //
    const card = document.createElement('article');
    card.classList.add('post-card', 'site-box-shadow');

    // Generate the html and css for each card.
    card.innerHTML = `
        <h2 class="post-card-title site-font-secondary-heading site-gradient-rtl-border-bottom">${normalizedCardData.short_desc}</h2>
        <div class="post-card-components site-gradient-ltr-border-bottom">
            <a class="post-card-img-anchor" href="/${encodeURIComponent(normalizedCardData.post_name)}">
                ${normalizedCardData.img_html}
            </a>
            <a class="post-card-longdesc site-font-std" href="/${encodeURIComponent(normalizedCardData.post_name)}">
                ${normalizedCardData.long_desc}
            </a>
            <a class="post-card-intro site-font-std" href="/${encodeURIComponent(normalizedCardData.post_name)}">
                ${normalizedCardData.intro}
            </a>
        </div>
        <div class="post-card-attrs">
            <span class="site-font-date-std">${normalizedCardData.post_created}</span>
            <a class="post-card-cat site-font-link-std" href="/postsearch?post_category=${encodeURIComponent(normalizedCardData.post_category)}">
                ${normalizedCardData.post_category}
            </a>
        </div>
        `.trim();
    postsearch.searchResultsContainerEle.appendChild(card);
}

postsearch.renderSearchFormatting = () => {

    // Add padding beneath post search controls container ele.
    postsearch.searchControlsContainerEle.classList.add(
        'postsearch-controls-container-searched'
    );
}
