class LAHdrFtr {

    /**
     * Creates a new LiberAnalytics Header & Footer class.
     * 
     */
    constructor() {

        /**
         * LAHdrFtrStyles is a js object representing the styles in the
         * site's header/footer to be set dynamically.
         * 
         * @type {object}
         * @public
         */
        this.LAHdrFtrStyles = 
        
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
        };
    }

    /**
     * Get the LAHdrFtrStyles object.
     * @return {object}
     */    
    getLAHdrFtrStyles = () => {
        return this.LAHdrFtrStyles;
    }

} // End class LAHdrFtr.
