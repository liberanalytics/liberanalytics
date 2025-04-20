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

simulate.submitData = function(fxCallback) {

    simulate.simulateFormData = new FormData(simulate.simulateFormEle);
    // for (formDatum of simulate.simulateFormData)
    // {
    //     console.log(formDatum[0], formDatum[1]);
    // }
    
    fetch(

        '/liberanalytics/site/api/postsearch.php',
        {
            method: "POST",
            body: simulate.simulateFormData,
        }

    )
    .then(response => response.json())
    .then(response => console.log(response));



}

