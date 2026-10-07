import * as LArenderor from "./modules/LArenderor.js";

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
    semanticsearch.errorEle = document.querySelector('#error_ele');
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

    LArenderor.clearError();
    if (!semanticsearch.nlpEle) return;
    const nlpRequest = semanticsearch.nlpEle.value;

    LArenderor.renderAjaxSpinner();

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
    .then(response => 
    {
        if (!response.ok) {
            throw new Error('Semantic search request failed.');
        }

        return response.json()
    })
    .then(semanticSearchResponse => {
        
        const postCategory = semanticSearchResponse.postCategory;
        const postTags = semanticSearchResponse.postTags;

        if (!postCategory && postTags.length === 0) {
            semanticsearch.rendorNoResults();
            return;
        }

        const urlPxs = new URLSearchParams();

        if (postCategory) {
            urlPxs.set('post_category', postCategory);
        }

        if (postTags) {
            const tags = Array.isArray(postTags)
                ? postTags.join(', ')
                : postTags;

            urlPxs.set('post_tags', tags);
        }

        window.location = `postsearch?${urlPxs.toString()}`;
    })
    .catch(error => {
        LArenderor.renderError({
            errorEle: semanticsearch.errorEle,
            errorMsg: 'Unable to complete semantic search. Please try again.'
        });
    })
    .finally(() => { LArenderor.unrenderAjaxSpinner(); })

}
