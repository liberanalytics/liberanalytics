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
        title: cardData.post_name,
        category: cardData.post_category,
        date: postsearch.normalizeCardDate(cardData.post_created)

    };
}

postsearch.normalizeCardDate = (cardDate) => {

}

postsearch.renderCard = (cardData) => {

    //
    const cardData = postsearch.normalizeCardData(cardData);
    const card = document.createElement('div');
    card.classList.add('site-box-shadow', 'search-card');
    postsearch.searchResultsContainer.appendChild(card);

}
