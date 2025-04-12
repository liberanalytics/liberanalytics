var posts = {};
posts.form = {};

window.addEventListener("load", () => 
{
    posts.setVars();
    posts.bindEvents();
});

posts.setVars = () => 
{
    posts.form = document.querySelector('#posts');
}

posts.bindEvents = () => 
{
    posts.form.addEventListener('submit', (event) => {
        event.preventDefault();
        posts.submit();
    });
}

posts.submit = () => {

    const lavx = new LAvx();
    const jsonPosts = lavx.getVxFormData(posts.form);

    //
    if (jsonPosts)
    
    //
    eleSearchResults = document.querySelector('#search_results_');
    
    {
        fetch
        (
            '/posts_handler.php',
            {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonPosts)
            }
        ).then(console.log(response => response.json()));
    }

}