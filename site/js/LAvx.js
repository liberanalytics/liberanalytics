class LAvx {

    /**
     * 
     * 
     */
    static vxReq = 'req';
    static vxType = 'vx';
    static vxLen = 'len';
    static vxId = 'id';

    /**
     * Creates a new LiberAnalytics validation class.
     * 
     */
    constructor() {

    }

    /** */
    vxName = (pxName, pxVal, pxLen, pxMinNumChars = 2) => {

        let vxName = 
        
            ( RegExp('^[A-Za-z]+(\'|\-)?[A-Za-z]+$').test(pxVal) && 
            ( (pxVal.length >= pxMinNumChars) && (pxVal.length <= pxLen) ) );

        return vxName;

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

} // End class LAvx.