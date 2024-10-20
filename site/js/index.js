window.onload = () => {

    setHeaderLineHeights();
}

function setHeaderLineHeights() {

    logoContainer   = document.querySelector('.site-logo');
    logoStyle       = window.getComputedStyle(logoContainer);
    logoHeight      = logoStyle.getPropertyValue('height'); 
    
    
    siteNames = document.getElementsByClassName('site-name');
    for (var i=0; i<siteNames.length; i++) {
        siteNames[i].style.lineHeight = logoHeight;
    }
    
    document.querySelector('#site_header_stmt').style.lineHeight = logoHeight;
}