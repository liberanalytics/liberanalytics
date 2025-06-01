var postsearch = {};
postsearch.form = {};

window.addEventListener('DOMContentLoaded', () => 
{
    postsearch.setVars();
    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.form = document.querySelector('#postsearch');
    postsearch.searchResultsContainer = document.querySelector('#postsearch_results');
}

postsearch.bindEvents = () => 
{
    postsearch.form.addEventListener('submit', (event) => {
        event.preventDefault();
        postsearch.submit();
    });
}

postsearch.submit = () => {    
    postsearch.submitSearch();
}

postsearch.submitSearch = () => {

    // Todo: Render ajax spinner.

    return fetch(
        '/liberanalytics/site/api/postsearch.php',
        {
            method: "POST", 
            headers: {
                'Content-Type': 'application/json'
            }
        }
    )
    .then(response => response.json())
    .then(searchResults => {
        postsearch.renderSearchResults(searchResults)
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
        intro: intro,
        search_img: search_img
    };
}

postsearch.normalizeCardDate = (cardDate) => {

    //
    const cardDate = new Date(cardDate);
    const normalizedCardDate = cardDate.toLocaleDateString('en-US',{
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    return normalizedCardDate;
}

postsearch.renderCard = (cardData) => {

    //
    const cardData = postsearch.normalizeCardData(cardData);
    const card = document.createElement('div');
    card.classList.add('site-box-shadow', 'search-card');
    postsearch.searchResultsContainer.appendChild(card);

}
