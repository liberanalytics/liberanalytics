import { LATagSuggest } from "./modules/LATagSuggest.js";
import * as LArenderor from "./modules/LArenderor.js";

var tagsearch = {};

window.addEventListener('DOMContentLoaded', () => {
    tagsearch.setVars();
    tagsearch.bindEvents();
});

tagsearch.setVars = () => {
    tagsearch.siteSearchParentEle = document.querySelector('#site_search_container_tags');
    tagsearch.siteSearchFormEle = document.querySelector('#site_search_form_tags');
    tagsearch.siteSearchEle = document.querySelector('#site_search_tags');
    tagsearch.tagSuggestionsEle = document.querySelector('#site_search_tag_suggestions');
}

tagsearch.bindEvents = () => {

    tagsearch.siteSearchFormEle.addEventListener('submit', (event) => {
        event.preventDefault();
        tagsearch.submitSiteSearch();
    });

    LATagSuggest(tagsearch.siteSearchParentEle, tagsearch.siteSearchEle, tagsearch.tagSuggestionsEle);
}

tagsearch.submitSiteSearch = () => {

    LArenderor.renderAjaxSpinner();
    const tags = tagsearch.siteSearchEle.value.trim().replace(/(,\s*)+$/, '');

    if (tags.length > 0) {
        window.location = `postsearch?post_tags=${encodeURIComponent(tags)}`;
    }

}
