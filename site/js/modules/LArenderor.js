const defaultErrorEle = document.querySelector('.error-msg');

export const clearError = (errorEle = defaultErrorEle) => {
    errorEle.textContent = '';
}

export const renderError = ({
    errorEle = defaultErrorEle,
    errorKey,
    errorValue
} = {}) => {
    if (errorEle) {
        errorEle.textContent = `${errorKey} value ${errorValue} is invalid.`
    }
}
