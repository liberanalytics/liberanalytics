window.onload = () => {

    setHeaderLineHeights();
}

function setHeaderLineHeights() {

    logoContainer   = document.querySelector('#site_logo');
    logoStyle       = window.getComputedStyle(logoContainer);
    logoHeight      = logoStyle.getPropertyValue('height');    
    document.querySelector('#site_name > *').style.lineHeight         = logoHeight,
    document.querySelector('#site_summary_stmt').style.lineHeight = logoHeight;
}