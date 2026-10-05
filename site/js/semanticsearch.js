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
    if (!semanticsearch.formEle) {
        console.warn('Search form element not found in DOM.');
        return;
    }

    semanticsearch.formEle.addEventListener('submit', (event) => {
        event.preventDefault();
        semanticsearch.submitNlpRequest();
    });
}

semanticsearch.submitNlpRequest = () =>
{

    if (!semanticsearch.nlpEle) return;
    const nlpRequest = semanticsearch.nlpEle.value;

    return fetch(
        '/api/semanticsearch',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nlpRequest: nlpRequest
            })
        }
    )
    .then(response => response.json())
    .then(semanticSearchResponse => {
        console.log(semanticSearchResponse);
    });

}
