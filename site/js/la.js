import { LATagSuggest } from "./modules/LATagSuggest.js";

var la = {};

window.addEventListener('DOMContentLoaded', () => {
    la.setVars();
    la.bindEvents();
});

la.setVars = () => {
    la.siteSearchParentEle = document.querySelector('#site_search_container_tags');
    la.siteSearchFormEle = document.querySelector('#site_search_form_tags');
    la.siteSearchEle = document.querySelector('#site_search_tags');
    la.tagSuggestionsEle = document.querySelector('#site_search_tag_suggestions');
}

la.bindEvents = () => {

    la.siteSearchFormEle.addEventListener('submit', (event) => {
        event.preventDefault();
        la.submitSiteSearch();
    });

    LATagSuggest(la.siteSearchParentEle, la.siteSearchEle, la.tagSuggestionsEle);
}

la.submitSiteSearch = () => {

    const tags = la.siteSearchEle.value.trim().replace(/(,\s*)+$/, '');

    if (tags.length > 0) {
        window.location = `postsearch?post_tags=${encodeURIComponent(tags)}`;
    }

}
