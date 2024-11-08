class LAvx {

    static vxReq = 'req';
    static vxType = 'vx';
    static vxLen = 'len';

    /**
     * Creates a new validation class.
     * 
     */
    constructor() {
        
        this.valid = false;

    }



    /** */
    vxName = (pxVal, pxLen) => {
        console.log(pxVal);
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

        for ( let i=0; i < vxData.elements.length; i++ ) {

            
            let 
                vxDataName = vxData[i].name,
                vxDataVal = vxData[i].value,
                vxDataReq = vxData[i].dataset[LAvx.vxReq],
                vxDataVx = vxData[i].dataset[LAvx.vxType],
                vxDataLen = vxData[i].dataset[LAvx.vxLen];

            //
            if (vxDataReq) {

                switch (vxDataVx) {

                    case 'vxName': this.vxName(vxDataVal, vxDataLen); break;
                    case 'vxEmail': this.vxEmail(vxDataVal, vxDataLen); break;
                    case 'vxAlphaNum': this.vxAlphaNum(vxDataVal, vxDataLen); break;
                    case 'vxMsg': this.vxMsg(vxDataVal, vxDataLen); break;

                }

            }

        }

    }

}
