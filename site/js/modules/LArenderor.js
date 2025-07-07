const getDefaultErrorEle = () => { 
    return document.querySelector('.error-msg');
}

const getAjaxOverlayEle = () => {

    const getAjaxOverlayEle = 
        (document.querySelector('.site-ajax-overlay')) ?
        document.querySelector('.site-ajax-overlay') :
        document.querySelector('.site-ajax-overlay-flex');
    return getAjaxOverlayEle;

}



export const clearError = ({
    errorEle = getDefaultErrorEle()
} = {}) => {
    errorEle.textContent = '';
}

export const renderError = ({
    errorEle = getDefaultErrorEle(),
    errorKey,
    errorValue
} = {}) => {
    if (errorEle) {
        errorEle.textContent = `${errorKey} value ${errorValue} is invalid.`;
    }
}

export const renderAjaxSpinner = () => {
    getAjaxOverlayEle().classList.remove('hidden');
}

export const unrenderAjaxSpinner = () => {
    getAjaxOverlayEle().classList.add('hidden');
}
