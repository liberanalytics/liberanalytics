import * as LAvx from "./modules/LAvx.js";

var contact = {};

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
        const eleMaxLen = formEle.dataset.len;
        const eleVxReq = formEle.dataset.req;
        const eleVxName = formEle.dataset.vx;
        const eleVx = LAvx[eleVxName];
        
        if (eleVxReq && typeof(eleVx) === 'function') {
            const isFormValid = eleVx(eleValue, eleMaxLen)
            if (!isFormValid) {
                formValid = false;
                //contact.rendorError()
            }
        }

    }

}

contact.rendorError = (key, errorValue) => {

}
