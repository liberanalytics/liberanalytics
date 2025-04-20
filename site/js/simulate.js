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

simulate.bindEvents = () =>
{
    simulate.simulateFormEle.addEventListener('submit', (event) => {
        event.preventDefault();
        simulate.submitClientData();
    });
}

simulate.wait = (ms) =>
{
    return new Promise(resolve => setTimeout(resolve, ms));
}


simulate.submitClientData = () =>
{
    simulate.wait(2000)
    .then(() => simulate.getServerData())
    .then(response => {
        console.log(response)
    })
    .catch(error => {
        console.error("Error during fetch: ", error);
    });
}

simulate.getServerData = () =>
{
    simulate.simulateFormData = new FormData(simulate.simulateFormEle);

    return fetch(
        '/liberanalytics/site/api/postsearch.php',
        {
            method: "POST",
            body: simulate.simulateFormData,
            headers: {
                'Content-Type': 'application/json'
            }
        }
    ).then(response => response.json());
}


