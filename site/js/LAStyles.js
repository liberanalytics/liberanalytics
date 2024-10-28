class LAStyles {

    /**
     * Creates a new style setting class.
     * 
     */
    constructor() {

    }

    /**
     * Set styles of element based on a source element's styles.
     * 
     * @param {object} pEleStylesToSet  -   JSON Representing which target element(s) & properties to set.
     */
    setTargettoSourceStyles(pEleStylesToSet) {

        // Check param is an object.
        if (typeof pEleStylesToSet === 'object' && pEleStylesToSet !== null) {        

            // Iterate through JSON data capturing the name value pairs of objects.
            for (const targetEleIdentifier in pEleStylesToSet) {
                for (const targetEleProp in pEleStylesToSet[targetEleIdentifier]) {
                    for (const sourceEleIdentifier in pEleStylesToSet[targetEleIdentifier][targetEleProp]) {
                        
                        // Set the target & source DOM elements as well as the source element's property/value.
                        var targetEle       = document.querySelectorAll(targetEleIdentifier),
                            sourceEle       = document.querySelector(sourceEleIdentifier),
                            sourceEleStyle  = window.getComputedStyle(sourceEle),
                            sourceEleProp   = pEleStylesToSet[targetEleIdentifier][targetEleProp][sourceEleIdentifier],
                            sourceEleVal    = sourceEleStyle.getPropertyValue(sourceEleProp);

                        // Apply the source element's property value to the target element(s) property.
                        for (let i = 0; i < targetEle.length; i++) {
                            targetEle[i].style[targetEleProp] = sourceEleVal;
                        }
            }}}}

    }   // End f(x) setTargettoSourceStyles.

}   // End class LAStyles.