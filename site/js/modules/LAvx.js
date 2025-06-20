

	/**
	 * 
	 * @param {*} pxVal 
	 * @param {*} pxLen 
	 * @param {*} pxMinNumChars 
	 * @returns {boolean}
	 */
	export const vxName = (pxVal, pxLen, pxMinNumChars = 2) => {

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
	export const vxEmail = (pxVal, pxLen) => {
		
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
	export const vxAlphaNum = (pxVal, pxLen, pxMinNumChars = 3) => {  

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
	export const vxText = (pxVal, pxLen, pxMinNumChars = 3) => 
	{
		const vxText = 
			RegExp('^[A-Za-z0-9 \\,\\.\\?\\!\\;\\/\\-]{' + pxMinNumChars.toString() + ',' + pxLen.toString() + '}$').test(pxVal);
		return vxText;        
	}

	/**
	 * 
	 * @param {*} pxVal
	 * @returns {string}
	 */
	export const szText = (pxVal) => 
	{
		const szText = String(pxVal).replace(/\s{2,}/, ' ');
		return szText;
	}
