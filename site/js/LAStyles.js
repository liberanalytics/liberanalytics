class LAStyles {

    /**
     * Creates a new LiberAnalytics style setting class.
     * 
     */
    constructor()
    {
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

    /**
     * Set styles of element based on a source element's styles.
     * 
     * @param {object} pEleStylesToSet  -   Javascript object which target element(s) & properties to set.
     */
    setTargettoSourceStyles(pEleStylesToSet) {

        // Check param is an object.
        if (typeof pEleStylesToSet === 'object' && pEleStylesToSet !== null) {        

            // Iterate through object data capturing the name value pairs of objects.
            for (const targetEleIdentifier in pEleStylesToSet) {
                for (const targetEleProp in pEleStylesToSet[targetEleIdentifier]) {
                    for (const sourceEleIdentifier in pEleStylesToSet[targetEleIdentifier][targetEleProp]) {
                        
                        // Set the target & source DOM elements as well as the source element's property/value.
                        var targetEle               = document.querySelectorAll(targetEleIdentifier),
                            sourceEle               = document.querySelector(sourceEleIdentifier),
                            sourceEleStyle          = window.getComputedStyle(sourceEle),
                            sourceEleProp           = pEleStylesToSet[targetEleIdentifier][targetEleProp][sourceEleIdentifier],
                            sourceEleValCss         = sourceEleStyle.getPropertyValue(sourceEleProp),
                            sourceEleValReadOnly    = sourceEle[sourceEleProp];

                        // Apply the source element's property value to the target element(s) property.
                        for (let i = 0; i < targetEle.length; i++) {

                            // Check if the property to set to is a CSS property or a 
                            // JS HtmlElement interface read only property.
                            let sourceEleVal = sourceEleValCss ? sourceEleValCss : sourceEleValReadOnly;

                            // Add px as default unit when no unit is attendant.
                            sourceEleVal = ( (sourceEleVal.toString().slice(-2)) == 'px' ) ? sourceEleVal : sourceEleVal.toString() + 'px';

                            targetEle[i].style[targetEleProp] = sourceEleVal;
                        }
            }}}}

    }   // End f(x) setTargettoSourceStyles.

}   // End class LAStyles.