var la = {};

import { LATagSuggest } from "./modules/LATagSuggest.js";

window.addEventListener('DOMContentLoaded', () => {
    la.setVars();
    la.bindEvents();
});

la.setVars = () => {
    la.siteSearchFormEle = document.querySelector('#site_search_form');
    la.siteSearchParentEle = document.querySelector('#site_search');
    la.siteSearchEle = document.querySelector('#site_search_text');
    la.tagSuggestionsEle = document.querySelector('#laheader_tag_suggestions');
}

la.bindEvents = () => {

    la.siteSearchFormEle.addEventListener('submit', (event) => {
        event.preventDefault();
        window.location =

    });

    LATagSuggest(la.siteSearchParentEle, la.siteSearchEle, la.tagSuggestionsEle);
}
