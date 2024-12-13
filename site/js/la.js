var la = {};

window.onload = () => {
    la.setStyles();
}

la.setStyles = () => {
    
    //
    const laStyle = new LAStyles();
    const laHdrFtr = new LAHdrFtr();

    //
    laStyle.setTargettoSourceStyles(laHdrFtr.getLAHdrFtrStyles());
}
