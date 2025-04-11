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
    const jsonContact = lavx.getVxFormData(contact.form);

    //
    if (jsonContact) {
        // Submit data to endpoint api.
    }

}