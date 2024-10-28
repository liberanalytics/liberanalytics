window.onload = () => {

    var la = new LAStyles();
    la.setTargettoSourceStyles(

        // Site footer statment to be centered according to logo height.
        {
            '.site-name' : {
                'lineHeight' : {
                    '.site-logo-c' : 'height'
                }
            },

            // Site footer statment container margin should align with site name text.
            '#site_footer_stmt' : { 
                'margin-left' : {
                    '.site-logo-c' : 'width'
                }
            },

            // Site header statment to be centered according to logo height.
            '#site_header_stmt' : {
                'lineHeight' : {
                    '.site-logo-c' : 'height'
                }
            },

            // Site header statment to be centered according to logo height.
            '#site_footer_nav' : {
                'lineHeight' : {
                    '.site-logo-c' : 'height'
                }
            }
        }
    );
}