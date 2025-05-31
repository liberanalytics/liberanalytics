var postsearch = {};
postsearch.form = {};

window.addEventListener("load", () => 
{
    postsearch.setVars();
    postsearch.bindEvents();
});

postsearch.setVars = () => 
{
    postsearch.form = document.querySelector('#postsearch');
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
    console.log(searchResults)
}
