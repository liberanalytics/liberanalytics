var posts = {};
posts.form = {};

window.addEventListener("load", () => 
{
    posts.setVars();
    posts.bindEvents();
});

posts.setVars = () => 
{
    posts.form = document.querySelector('#search');
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
    {
        //
        eleSearchResults = document.querySelector('#search_results');

        fetch
        (
            'api/posts_handler.php',
            {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonPosts)
            }
        ).then(function (response) {
            return response.json();
        }).then(function(data){
            console.log(data);
        });
    }
}
