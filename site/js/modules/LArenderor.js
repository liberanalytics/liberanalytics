const getDefaultErrorEle = () => { 
    return document.querySelector('#error_ele');
}

const getAjaxOverlayEle = () => {
    const getAjaxOverlayEle = 
        document.querySelector('.site-ajax-overlay') ||
        document.querySelector('.site-ajax-overlay-flex');
    return getAjaxOverlayEle;
}

const getAjaxSpinnerEle = () => {
    const getAjaxSpinnerEle = document.querySelector('.site-ajax-spinner-8spoke');
    return getAjaxSpinnerEle;
}

export const clearError = ({
    errorEle = getDefaultErrorEle()
} = {}) => {
    errorEle.textContent = '';
}

export const renderError = ({
    errorEle = getDefaultErrorEle(),
    errorMsg = null,
    errorKey,
    errorValue
} = {}) => {
    if (errorEle) {
        const renderMsg =
            errorMsg ?? `${errorKey} value ${errorValue} is invalid.`;

        errorEle.textContent = renderMsg;
    }
}

export const renderAjaxSpinner = () => {
    getAjaxOverlayEle().classList.remove('site-ajax-hidden');
    getAjaxSpinnerEle().classList.remove('site-ajax-hidden');
}

export const unrenderAjaxSpinner = () => {
    getAjaxOverlayEle().classList.add('site-ajax-hidden');
    getAjaxSpinnerEle().classList.add('site-ajax-hidden');
}
