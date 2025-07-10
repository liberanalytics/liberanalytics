import * as LAvx from "./modules/LAvx.js";
import * as LArenderor from "./modules/LArenderor.js";

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
        contact.submitContact();
    });
}

contact.submitContact = () => {

    LArenderor.clearError();
    let formValid = true;
    const formData = new FormData(contact.form);
    const formDataJSON = {};

    for (const formEle of contact.form.elements) {

        const eleName = formEle.name;
        const eleValue = formData.get(eleName);
        const eleMaxLen = formEle.dataset.len;
        const eleVxReq = formEle.dataset.req;
        const eleVxName = formEle.dataset.vx;
        const eleVx = LAvx[eleVxName];

        if (eleName) {
            formDataJSON[eleName] = eleValue;
        }
        
        if (eleVxReq && typeof(eleVx) === 'function') {
            const isFormValid = eleVx(eleValue, eleMaxLen);
            if (!isFormValid) {
                formValid = false;                
                LArenderor.renderError({errorKey: eleName, errorValue: eleValue});
                return;
            }
        }
    }

    LArenderor.renderAjaxSpinner();    

    // Fetch the posts.
    return fetch(
        '/api/contact',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formDataJSON)
        }
    )
    .then(response => response.json())
    .then(contactResult => {
        //console.log(contactResult);
        contact.contactSuccess(contactResult);
    })
    .catch(error => {
        console.error('Error: ', error);
    })
    .finally(() => {
        LArenderor.unrenderAjaxSpinner();
    });

}

contact.contactSuccess = (contactResult) => {
    const firstName = contactResult['first_name'];
    
    //console.log(firstName);
    window.location.href = `contactsubmit?name=${encodeURIComponent(firstName)}`;
}
