import * as LAvx from "./modules/LAvx.js";
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
    LArenderor.renderAjaxSpinner();
    const nlpRequest = semanticsearch.nlpEle.value;

    if (!LAvx.vxText(nlpRequest, 64)) {
        LArenderor.renderError({
            errorMsg: nlpRequest + 'is invalid'
        });
        LArenderor.unrenderAjaxSpinner();
        return;
    }

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
    .then(response => {
        return response.json().then(responseData => {

            if (!response.ok) {
                throw new Error(
                    responseData.error ?? 'Semantic search request failed.'
                );
            }

            return responseData;
        });
    })
    .then(semanticSearchResponse => {

        const postCategory = semanticSearchResponse.postCategory;
        const postTags = semanticSearchResponse.postTags;

        if (!postCategory && postTags.length === 0) {
            throw new Error('No matching posts to show.');
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
            errorMsg: error.message
        });
    })
    .finally(() => {
        LArenderor.unrenderAjaxSpinner();
    });

}
