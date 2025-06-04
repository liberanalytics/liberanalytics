/**
 * 
 * @param {*} inputParentEle 
 * @param {*} inputEle 
 * @param {*} tagSuggestionsEle 
 * 
 * 
 */
export function LATagSuggest(inputParentEle, inputEle, tagSuggestionsEle) {

    let tagFetchTimeout;

    inputParentEle.style.position = 'relative';


    //
    inputEle.addEventListener('keyup', () => {
        clearTimeout(tagFetchTimeout);
        tagFetchTimeout = setTimeout(getTagSuggestions, 250);
    });

    //
    inputEle.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            tagSuggestionsEle.innerHTML = '';
        }
    });

    //
    document.addEventListener('click', (event) => {
        if (!inputEle.contains(event.target) && !tagSuggestionsEle.contains(event.target)) {
            tagSuggestionsEle.innerHTML = '';
        }
    });


    const getTagSuggestions = () => {
        
        const allTags = inputEle.value;
        const lastTag = allTags.split(',').pop().trim();

        if (!lastTag) {
            tagSuggestionsEle.innerHTML = '';
            return;
        }

        fetch(

            `/liberanalytics/site/api/getposttagslike.php?post_tag_term=${encodeURIComponent(lastTag)}`,
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            }
        )
        .then(response => response.json())
        .then(tagSuggestions => renderTagSuggestions(tagSuggestions, allTags))
        .catch(err => console.error('Tag fetch error:', err));

    };

    const renderTagSuggestions = (tagSuggestions, allTags) => {
        
        tagSuggestionsEle.innerHTML = '';

        tagSuggestions.forEach(tagSuggestion => {

            const tagItem = document.createElement('div');
            tagItem.classList.add('tagsuggestion-item');
            tagItem.textContent = tagSuggestion.post_tag;

            tagItem.addEventListener('click', () => {

                let tags = allTags.split(',');
                tags[tags.length - 1] = ` ${tagSuggestion.post_tag}`;
                inputEle.value = tags.join(',').trim() + ', ';
                inputEle.focus();
                tagSuggestionsEle.innerHTML = '';

            });

            tagSuggestionsEle.appendChild(tagItem);

        });

    }


}


