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
    vxName = (pxVal, pxLen, pxMinNumChars = 2) => {

        const vxName = 
        
            ( RegExp('^[A-Za-z]+(\'|\-)?[A-Za-z]+$').test(pxVal) && 
            ( (pxVal.length >= pxMinNumChars) && (pxVal.length <= pxLen) )
            );

        return vxName;

    }

    /** */
    vxEmail = (pxVal, pxLen) => {
        
        const vxEmail =
        
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(pxVal);
 
        return vxEmail;

    }
    
    /** */
    vxAlphaNum = (pxVal, pxLen, pxMinNumChars = 3) => {  

        const vxAlphaNum = 

            RegExp('^[A-Za-z0-9 ]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);

        return vxAlphaNum;
    }
    
    /** */
    vxMsg = (pxVal, pxLen, pxMinNumChars = 3) => {

        const vxMsg = 

            RegExp('^[A-Za-z0-9 \\.\\?\\!\\;\\/\\-]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);

        return vxMsg;        
    }

} // End class LAvx.