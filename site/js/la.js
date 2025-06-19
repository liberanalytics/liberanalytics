var la = {};

import { LATagSuggest } from "./modules/LATagSuggest.js";

window.addEventListener('DOMContentLoaded', () => {
    la.setvars();
    la.bindEvents();
});

la.setVars = () => {
    la.siteSearchParentEle = document.querySelector('#site_search');
    la.siteSearchEle = document.querySelector('#site_search_text');
    la.tagSuggestionsEle = document.querySelector('#laheader_tag_suggestions');
}

la.bindEvents = () => {
    LATagSuggest(la.siteSearchParentEle, la.siteSearchEle, la.tagSuggestionsEle);
}
