import { LATagSuggest } from "./modules/LATagSuggest.js";

var la = {};

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
        la.submitSiteSearch();
    });

    LATagSuggest(la.siteSearchParentEle, la.siteSearchEle, la.tagSuggestionsEle);
}

la.submitSiteSearch = () => {

    const tags = la.siteSearchEle.value.trim().replace(/(,\s*)+$/, '');

    if (tags.length > 0) {
        window.location = `postsearch.php?post_tags=${encodeURIComponent(tags)}`;
    }

}
