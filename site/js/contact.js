var contact = {};

import * as LAvx from "./modules/LAvx.js";

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

    let formValid = true;
    const formData = new FormData(contact.form);

    for (const formEle of contact.form.elements) {

        const eleName = formEle.name;
        const eleValue = formData.get(eleName);
        const eleVx = formEle.dataset.vx;
        
        var tf = eleVx(eleValue);

        console.log()
    }

}

contact.rendorError = (key, errorValue) => {

}
