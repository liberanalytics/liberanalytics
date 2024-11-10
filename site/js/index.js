var index = {};

window.onload = () => {
    index.setStyles();
}

index.setStyles = () => {
    
    const laStyle = new LAStyles();
    const laHdrFtr = new LAHdrFtr();

    //
    laStyle.setTargettoSourceStyles(laHdrFtr.getLAHdrFtrStyles());
}