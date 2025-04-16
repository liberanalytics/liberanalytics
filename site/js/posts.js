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


}
