simulate = {};

// let simulateFormEle = "";

window.addEventListener("load", () => {
    simulate.setModuleVars();
    simulate.bindEvents();
});

simulate.setModuleVars = () =>
{
    simulate.simulateFormEle = document.querySelector('#data_form');
    simulate.simulateDataEle = document.querySelector('#simulate_data');
}

simulate.displayData = () =>
{
    simulate.simulateDataEle.innerHTML = "<h1>Data Submitted</h1>";
}

simulate.bindEvents = () =>
{
    simulate.simulateFormEle.addEventListener('submit', (event) => {
        event.preventDefault();
        simulate.submitData(simulate.displayData);
    });
}

simulate.submitData = function (fxCallBack) {

    // simulate.simulateFormData = new FormData(simulate.simulateFormEle);

    setTimeout(() => {
        console.log('submit');
    }, 2000);
    fxCallBack();

}
