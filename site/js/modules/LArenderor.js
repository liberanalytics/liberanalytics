const getDefaultErrorEle = () => { 
    return document.querySelector('.error-msg');
}

const getAjaxOverlayEle = () => {
    return document.querySelector('.site-ajax-overlay');
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
    const overlayEle = getAjaxOverlayEle();
    overlayEle.classList.remove('.hidden');
}

export const unrenderAjaxSpinner = () => {
    const overlayEle = getAjaxOverlayEle();
    overlayEle.classList.add('.hidden');
}