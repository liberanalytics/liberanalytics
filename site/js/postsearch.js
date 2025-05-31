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

    return fetch(
        '/liberanalytics/site/api/postsearch.php',
        {
            method: "POST",
            body:  
            headers: {
                'Content-Type': 'application/json'
            }
        }
    )

}
