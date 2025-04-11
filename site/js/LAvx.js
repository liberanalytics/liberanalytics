class LAvx {

	/**
	 * 
	 * 
	 */
	static vx
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
	vxText = (pxVal, pxLen, pxMinNumChars = 3) => 
	{
		const vxText = 
			RegExp('^[A-Za-z0-9 \\.\\?\\!\\;\\/\\-]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);
		return vxText;        
	}

	/**
	 * 
	 * @param {*} pxVal
	 * @returns {string}
	 */
	szText = (pxVal) => 
	{
		const szText = String(pxVal).replace(/\s{2,}/, ' ');
		return szText;
	}

	/**
	 * 
	 * @param {*} pxForm 
	 * @returns {Boolean}
	 */
	getVxFormData = (pxForm) => 
	{
		//
		const vxData = pxForm;
		const vxFormComposite = parseInt(pxForm.dataset.composite);
		const vxFormErrorEle = pxForm.dataset.erroreleid;
		let errorMsg = '';
		let vxFormValidated = false;
		let formValid = true;

	
		//
		document.querySelectorAll('.error-msg').forEach(element => {
			element.textContent = '';
		});	
	
		for ( let i=0; i < vxData.elements.length; i++ )
		{
			//
			let 
				vxDataType = vxData[i].type,
				vxDataName = vxData[i].name,
				vxDataVal = this.szText(vxData[i].value),
				vxDataVx = vxData[i].dataset[LAvx.vxType],
				vxDataReq = null,
				vxDataLen = vxData[i].dataset[LAvx.vxLen],
				vxDataId = vxData[i].dataset[LAvx.vxId];

				// See if the form element control is a required validation.
				if (!vxFormComposite)
				{
					vxDataReq = vxData[i].dataset[LAvx.vxReq];
				}
				else
				{
					if (['text', 'textarea'].includes(vxDataType))
					{
						vxDataReq = (String(vxDataVal).length > 0) ? 1 : 0;
					}
					else if (vxDataType == 'select-one')
					{
						vxDataReq = (String(vxDataVal).toLowerCase().includes('select')) ? 0 : 1;
					}
				}
	
			// If form is non composite, check each control value. If form is composite, check each non-empty
			// control value.
			if (parseInt(vxDataReq))
			{
				//
				vxFormValidated = true;
				//
				if (!this[vxDataVx](vxDataVal, vxDataLen))
				{	
					const errorMsg= vxDataId + ' is invalid.';
					const laStyles = new LAStyles();
					laStyles.setElementText('#' + vxFormErrorEle, errorMsg);

					// let error_ele_id = vxDataName.includes('name') ? 'error_name' : vxDataName.replace('contact', 'error');
					// error_ele_id = '#' + error_ele_id;
					// document.querySelector(error_ele_id).textContent = error_msg;
					formValid = false;	
				}
			}
		}        

		return formValid;

	}

} // End class LAvx.