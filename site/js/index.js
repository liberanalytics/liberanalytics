window.onload = () => {

    setHeaderLineHeights();
}

function setHeaderLineHeights() {

    logoContainer   = document.querySelector('.site-logo-c');
    logoStyle       = window.getComputedStyle(logoContainer);
    logoHeight      = logoStyle.getPropertyValue('height'); 
    logoWidth       = logoStyle.getPropertyValue('width'); 
    
    // 
    siteNames = document.getElementsByClassName('site-name');
    for (var i=0; i<siteNames.length; i++) {
        siteNames[i].style.lineHeight = logoHeight;
    }

    //
    document.getElementById('site_header_stmt').style.lineHeight = logoHeight;

    //
    document.getElementById('site_footer_nav_c').style.lineHeight = logoHeight;

    //
    document.getElementById('site_footer_stmt').style.marginLeft = logoWidth;

}