var contact = {};
contact.form = {};

window.onload = () => {
    contact.setVars();
    contact.setStyles();
    contact.bindEvents();
}

contact.setVars = () => {
    contact.form = document.querySelector('#contact');
}

contact.setStyles = () => {

    const laStyle = new LAStyles();
    const laHdrFtr = new LAHdrFtr();

    //
    laStyle.setTargettoSourceStyles(laHdrFtr.getLAHdrFtrStyles());

    laStyle.setTargettoSourceStyles()
    
}

contact.bindEvents = () => {
    contact.form.addEventListener('submit', (event) => {
        event.preventDefault();
        contact.submit();
    });
}

contact.submit = () => {

    const vxData = contact.form;
    const lavx = new LAvx();
    let formValid = true;

    //
    document.querySelectorAll('.error-msg').forEach(element => {
        element.textContent = '';
    });


    for ( let i=0; i < vxData.elements.length; i++ ) {

        //
        let 
            vxDataName = vxData[i].name,
            vxDataVal = vxData[i].value,
            vxDataReq = vxData[i].dataset[LAvx.vxReq],
            vxDataVx = vxData[i].dataset[LAvx.vxType],
            vxDataLen = vxData[i].dataset[LAvx.vxLen],
            vxDataId = vxData[i].dataset[LAvx.vxId];

        //
        if (vxDataReq) {

            //
            if (!lavx[vxDataVx](vxDataVal, vxDataLen)) {

                const error_msg = vxDataId + ' is invalid.';
                let error_ele_id = vxDataName.includes('name') ? 'error_name' : vxDataName.replace('contact', 'error');
                error_ele_id = '#' + error_ele_id;
                document.querySelector(error_ele_id).textContent = error_msg;
                formValid = false;

            }
        }
    }

    //
    if (formValid) {
        // Submit data to endpoint api.
    }

}