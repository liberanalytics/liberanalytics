var contact = {};
contact.form = {};

window.onload = () => {
    contact.setVars();
    contact.bindEvents();
}

contact.setVars = () => {
    contact.form = document.querySelector('#contact');
}

contact.bindEvents = () => {
    contact.form.addEventListener('submit', (event) => {
        event.preventDefault();
        contact.submit();
    });
}

contact.submit = () => {

    lavx = new LAvx();

    if (lavx.vx(contact.form)) {
        // Submit to contact endpoint api.
    }

}