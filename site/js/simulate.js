simulate = {};

// let simulateFormEle = "";

window.addEventListener("load", () => {
    simulate.setModuleVars();
});

simulate.setModuleVars = () =>
{

    // inputEle.value = 
    // inputEle.value.trim().replace(/,*\s*[a-zA-Z-],\s*$/, '') + 
    // selectedSuggestionItem + ', ';

    let term = 'aws';
    let selection = 'AWS-EC2';

    let result = term.trim().replace(/,?\s?[A-Za-z]{2,},?\s?$/, '') + selection + ', ';

    console.log(result);
}
