	var semanticsearch = {};

window.addEventListener('DOMContentLoaded', () => 
{
    semanticsearch.setVars();
    semanticsearch.bindEvents();
});

semanticsearch.setVars = () =>
{
    semanticsearch.formEle = document.querySelector('#site_search_form_nlp');
    semanticsearch.nlpEle = document.querySelector('#site_search_nlp');
}

semanticsearch.bindEvents = () =>
{
    semanticsearch.formEle.addEventListener('submit', (event) => {
        console.log('submit caught');

        event.preventDefault();

        semanticsearch.submitNlpRequest();
    });
}

semanticsearch.submitNlpRequest = () =>
{
    const nlpRequest = semanticsearch.nlpEle.value;

    console.log(nlpRequest);

    // return fetch(
    //     '/api/semanticsearch',
    //     {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({
    //             nlpRequest: nlpRequest
    //         })
    //     }
    // );    
}