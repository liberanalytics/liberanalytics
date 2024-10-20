window.onload = () => {

    setHeaderLineHeights();
}

function setHeaderLineHeights() {

    logoContainer   = document.querySelector('#site_header_logo');
    logoStyle       = window.getComputedStyle(logoContainer);
    logoHeight      = logoStyle.getPropertyValue('height');    
    document.querySelector('#site_header_name > *').style.lineHeight = logoHeight,
    document.querySelector('#site_header_stmt').style.lineHeight = logoHeight;
}