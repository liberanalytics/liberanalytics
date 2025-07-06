var contactSubmit = {};

window.addEventListener('DOMContentLoaded', () => {
    contactSubmit.setVars();
    contactSubmit.populateName();
});

contactSubmit.setVars = () => {
    contactSubmit.contactName = document.querySelector('#contact_name');
}

contactSubmit.populateName = () => {
    const urlPxs = new URLSearchParams(window.location.search);
    const contactName = urlPxs.get('name');
    if (contactName) {
        contactSubmit.contactName.textContent = contactName;
    }
}
