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

    // If no selection has been made
    if ()
    {
        LAvx.setErrorMsg();
    }



    //
    if (lavx.vxForm(posts.form)) {
        // Submit data to endpoint api.
    }

}