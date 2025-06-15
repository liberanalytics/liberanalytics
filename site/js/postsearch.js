var postsearch = {};

import { LATagSuggest } from "./modules/LATagSuggest.js";

window.addEventListener('DOMContentLoaded', () => 
{
    postsearch.setVars();
    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.formEle = document.querySelector('#postsearch');
    postsearch.categoryEle = document.querySelector('#postsearch_category');
    postsearch.tagsParentEle = document.querySelector('#postsearch_tags_w');
    postsearch.tagsEle = document.querySelector('#postsearch_tags');
    postsearch.searchFiltersContainerEle = document.querySelector('#postsearch_filters');
    postsearch.searchResultsContainerEle = document.querySelector('#postsearch_results');
    postsearch.tagSuggestionsEle = document.querySelector('#postsearch_tag_suggestions');

}

postsearch.bindEvents = () => 
{
    postsearch.formEle.addEventListener('submit', (event) => {
        event.preventDefault();
        postsearch.submit();
    });

    LATagSuggest(postsearch.tagsParentEle, postsearch.tagsEle, postsearch.tagSuggestionsEle);
}

postsearch.submit = () => {
    postsearch.normalizeFilterData();
    postsearch.submitSearch();
}



postsearch.submitSearch = () => {

    // Todo: Render ajax spinner.

    return fetch(
        '/liberanalytics/site/api/postsearch.php',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(
                postsearch.normalizeFilterData('filters')
            )

        }
    )
    .then(response => response.json())
    .then(searchResults => {
        postsearch.renderSearch(searchResults);
    })
    .catch(error => {
        console.error('Error: ', error);
    })
    .finally(() => {
        // Todo: Unrender ajax spinner.
    });
}

postsearch.renderSearch = (searchResults) => {

    //
    postsearch.renderSearchFilters();
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
            <a class="search-card-img" href="${encodeURIComponent(normalizedCardData.post_name)}.php">
                <img src="assets/${normalizedCardData.search_img}">
            </a>
            <a class="search-card-longdesc" href="${encodeURIComponent(normalizedCardData.post_name)}.php">
                ${normalizedCardData.long_desc}
            </a>
            <a class="search-card-intro" href="${encodeURIComponent(normalizedCardData.post_name)}.php">
                ${normalizedCardData.intro}
            </a>
        </div>
        <div class="search-card-attrs">
            <span class="search-card-date">${normalizedCardData.post_created}</span>
            <a class="search-card-cat" href="${encodeURIComponent(normalizedCardData.post_category)}.php">
                ${normalizedCardData.post_category}
            </a>
        </div>
        `.trim();
    postsearch.searchResultsContainerEle.appendChild(card);
}

postsearch.renderSearchFilters = () => {
    postsearch.searchFiltersContainerEle.innerHTML = '';
    postsearch.renderFilters();
}


postsearch.normalizeFilterData = (dataType) => {

    const DEFAULT_CATEGORY_VALUE = 'Select Category';
    const DEFAULT_CATEGORY_CAPTION = 'All Categories';
    const DEFAULT_TAGS_CAPTION = 'All Tags';
    
    //
    let categoryFilterTrimmed = postsearch.categoryEle.value.trim();
    let categoryFilter = categoryFilterTrimmed === DEFAULT_CATEGORY_VALUE ? null : categoryFilterTrimmed;
    let categoryCaption = categoryFilter === DEFAULT_CATEGORY_VALUE ? DEFAULT_CATEGORY_CAPTION : categoryFilter;

    //
    let tagsFilterTrimmed = postsearch.tagsEle.value.trim().replace(/,*$/, '');
    let tagsFilter = (tagsFilterTrimmed.length < 1) ? null : tagsFilterTrimmed;
    let tagsCaption = tagsFilter === '' ? DEFAULT_TAGS_CAPTION : tagsFilter;


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

postsearch.renderFilters = () => {

    //
    const normalizedCaptionData = postsearch.normalizeFilterData('captions');

    //
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
    filters.classList.add('site-gradient-border-bottom');

    filters.innerHTML = `
        <h2 class="search-results-heading">Showing results for:</h2>
        <div id="postsearch_filter_chips">

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
