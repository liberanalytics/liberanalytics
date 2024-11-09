class LAvx {

    static vxReq = 'req';
    static vxType = 'vx';
    static vxLen = 'len';

    /**
     * Creates a new validation class.
     * 
     */
    constructor() {

    }

    /** */
    vxName = (pxName, pxVal, pxLen, pxMinNumChars = 2) => {

        if (! ( RegExp('^[A-Za-z]+(\'|\-)?[A-Za-z]+$').test(pxVal) && 
        ( (pxVal.length >= pxMinNumChars) && (pxVal.length <= pxLen) ) ) ) {
            return 'Invalid';
        }
    }

    /** */
    vxEmail = (pxVal, pxLen) => {
        console.log(pxVal);
    }
    
    /** */
    vxAlphaNum = (pxVal, pxLen) => {
        console.log(pxVal);
    }
    
    /** */
    vxMsg = (pxVal, pxLen) => {
        console.log(pxVal);
    }     

    /** */
    vx = (vxData) => {

        

    }

}
