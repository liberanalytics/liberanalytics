window.onload = () => {

    setHeaderLineHeights();
}

function setHeaderLineHeights() {

    logoContainer   = document.querySelector('#site_logo_w');
    logoStyle       = window.getComputedStyle(logoContainer);
    logoHeight      = logoStyle.getPropertyValue('height');    
    document.querySelector('#site_name_w > *').style.lineHeight         = logoHeight,
    document.querySelector('#site_summary_stmt_w').style.lineHeight = logoHeight;
}