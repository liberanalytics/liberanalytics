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
    const rawName = urlPxs.get('name');
    if (rawName) {
        const contactName = rawName.replace(/[<>"']/g, '');
        contactSubmit.contactName.textContent = contactName;
    }
    else {
        window.location.href = 'contact.php';
    }
}
