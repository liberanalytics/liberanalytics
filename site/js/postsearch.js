

var postsearch = {};
postsearch.form = {};

import { LATagSuggest } from "./modules/LATagSuggest.js";

window.addEventListener('DOMContentLoaded', () => 
{
    postsearch.setVars();
    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.form = document.querySelector('#postsearch');
    postsearch.category = document.querySelector('#postsearch_category');

    postsearch.tagsParentEle = document.querySelector('#postsearch_tags_w');
    postsearch.tags = document.querySelector('#postsearch_tags');

    postsearch.searchResultsContainer = document.querySelector('#postsearch_results');
    postsearch.tagSuggestionsBox = document.querySelector('#postsearch_tag_suggestions');
}

postsearch.bindEvents = () => 
{
    postsearch.form.addEventListener('submit', (event) => {
        event.preventDefault();
        postsearch.submit();
    });

    LATagSuggest(postsearch.tagsParentEle, postsearch.tags, postsearch.tagSuggestionsBox);

    
}

postsearch.submit = () => {    
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
            body: JSON.stringify({
                'postCategory': postsearch.category.value
            })

        }
    )
    .then(response => response.json())
    .then(searchResults => {
        postsearch.renderSearchResults(searchResults);
    })
    .catch(error => {
        console.error('Error: ', error);
    })
    .finally(() => {
        // Todo: Unrender ajax spinner.
    });
}

postsearch.renderSearchResults = (searchResults) => {

    //
    postsearch.searchResultsContainer.innerHTML = '';

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
    postsearch.searchResultsContainer.appendChild(card);
}

