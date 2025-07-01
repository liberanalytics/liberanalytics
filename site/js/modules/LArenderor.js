export const clearError = (ele = document.querySelector('.error-msg')) => {
    ele.textContent = '';
}

export const renderError = (ele, key, errorValue) => {
    ele.textContent = `${key} value ${errorValue} is invalid.`
}
