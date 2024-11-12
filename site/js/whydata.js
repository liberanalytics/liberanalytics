var blog = {};


window.onload = () => {
    blog.setStyles();
}


blog.setStyles = () => {

    //
    const laStyle = new LAStyles();
    const laHdrFtr = new LAHdrFtr();
    laStyle.setTargettoSourceStyles(laHdrFtr.getLAHdrFtrStyles());
    
}