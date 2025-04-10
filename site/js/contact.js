var contact = {};
contact.form = {};

window.addEventListener("load", () => {
    contact.setVars();
    contact.bindEvents();
});

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

    const lavx = new LAvx();

    //
    if (lavx.vxForm(contact.form)) {
        // Submit data to endpoint api.
    }

}