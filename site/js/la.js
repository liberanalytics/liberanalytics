var la = {};

window.addEventListener("load", () => {
    la.setStyles();
});

la.setStyles = () => {
    
    //
    const laStyle = new LAStyles();
    laStyle.setTargettoSourceStyles(laStyle.getLAHdrFtrStyles());
}
