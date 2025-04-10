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

	/**
	 * 
	 * @param {*} pxVal 
	 * @param {*} pxLen 
	 * @param {*} pxMinNumChars 
	 * @returns {boolean}
	 */
	vxName = (pxVal, pxLen, pxMinNumChars = 2) => {

		const vxName = 
		
			( RegExp('^[A-Za-z]+(\'|\-)?[A-Za-z]+$').test(pxVal) && 
			( (pxVal.length >= pxMinNumChars) && (pxVal.length <= pxLen) )
			);

		return vxName;

	}

	/**
	 * 
	 * @param {*} pxVal 
	 * @param {*} pxLen 
	 * @returns {Boolean}
	 */
	vxEmail = (pxVal, pxLen) => {
		
		const vxEmail =
		
			/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(pxVal);
 
		return vxEmail;

	}
	
	/**
	 * 
	 * @param {*} pxVal 
	 * @param {*} pxLen 
	 * @param {*} pxMinNumChars 
	 * @returns {Boolean}
	 */
	vxAlphaNum = (pxVal, pxLen, pxMinNumChars = 3) => {  

		const vxAlphaNum = 

			RegExp('^[A-Za-z0-9 ]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);

		return vxAlphaNum;
	}
	
	/**
	 * 
	 * @param {*} pxVal 
	 * @param {*} pxLen 
	 * @param {*} pxMinNumChars 
	 * @returns {Boolean}
	 */
	vxMsg = (pxVal, pxLen, pxMinNumChars = 3) => {

		const vxMsg = 

			RegExp('^[A-Za-z0-9 \\.\\?\\!\\;\\/\\-]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);

		return vxMsg;        
	}

	/**
	 * 
	 * @param {*} pxForm 
	 * @returns {Boolean}
	 */
	vxForm = (pxForm) => 
	{
		//
		const vxData = pxForm;		
		let formValid = true;
	
		//
		document.querySelectorAll('.error-msg').forEach(element => {
			element.textContent = '';
		});	
	
		for ( let i=0; i < vxData.elements.length; i++ ) 
		{
			//
			let 
				vxDataName = vxData[i].name,
				vxDataVal = vxData[i].value,
				vxDataReq = vxData[i].dataset[LAvx.vxReq],
				vxDataVx = vxData[i].dataset[LAvx.vxType],
				vxDataLen = vxData[i].dataset[LAvx.vxLen],
				vxDataId = vxData[i].dataset[LAvx.vxId];
	
			//
			if (vxDataReq)
			{
				//
				if (!this[vxDataVx](vxDataVal, vxDataLen))
				{	
					const error_msg = vxDataId + ' is invalid.';
					let error_ele_id = vxDataName.includes('name') ? 'error_name' : vxDataName.replace('contact', 'error');
					error_ele_id = '#' + error_ele_id;
					document.querySelector(error_ele_id).textContent = error_msg;
					formValid = false;	
				}
			}
		}        

		return formValid;
	}

	setErrorMsg = () => 
	{

	}

} // End class LAvx.