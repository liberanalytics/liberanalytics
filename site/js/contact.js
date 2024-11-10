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

    const la = new LAStyles();
    const laHdrFtr = new LAHdrFtr();

    //
    la.setTargettoSourceStyles(laHdrFtr.getLAHdrFtrStyles());
    
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

    for ( let i=0; i < vxData.elements.length; i++ ) {
            
        let 
            vxDataName = vxData[i].name,
            vxDataVal = vxData[i].value,
            vxDataReq = vxData[i].dataset[LAvx.vxReq],
            vxDataVx = vxData[i].dataset[LAvx.vxType],
            vxDataLen = vxData[i].dataset[LAvx.vxLen];

        //
        if (vxDataReq) {

            //
            let errorMsg = window["lavx"][vxDataVx](vxDataName, vxDataVal, vxDataLen);
            if (errorMsg) {
                // Populate error message to E.
                formValid = false;
            }
        }
    }

    if (formValid) {
        // Submit data to endpoint api.
    }

}