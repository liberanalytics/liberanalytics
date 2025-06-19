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
    let suggestionItemsIndex = -1;

    inputParentEle.style.position = 'relative';

    //
    inputEle.addEventListener('keyup', (event) => {

        if (['ArrowDown', 'ArrowUp', 'Escape', 'Enter'].includes(event.key)) {
            return;
        }

        clearTimeout(tagFetchTimeout);
        tagFetchTimeout = setTimeout(getTagSuggestions, 250);
    });

    //
    inputEle.addEventListener('keydown', (event) => {

        const suggestionItems = tagSuggestionsEle.children;
        const suggestionItemsCount = suggestionItems.length;
        

        if (event.key === 'Escape') {
            tagSuggestionsEle.innerHTML = '';
        }
        else if (event.key === 'ArrowDown') {

            event.preventDefault();
            if (suggestionItemsIndex < suggestionItemsCount - 1) {
                suggestionItemsIndex++;
            }
            updateActiveTagSuggestion();

        }
        else if (event.key === 'ArrowUp') {

            event.preventDefault();
            if (suggestionItemsIndex > 0) {
                suggestionItemsIndex--;
            }
            updateActiveTagSuggestion();

        }
        else if (event.key === 'Enter') {

            event.preventDefault();

            // Make sure there are suggestions and one has been selected in order to derive the
            // selected suggestion.
            if (suggestionItemsCount > 0 && suggestionItemsIndex >= 0) {
                const selectedSuggestionItem = suggestionItems[suggestionItemsIndex].textContent.trim();

                // Remove the search term before adding the selected suggestion item.
                let existingTags = 
                inputEle.value.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0);
                if (existingTags.length > 0) {
                    existingTags.pop();
                }

                existingTags.push(selectedSuggestionItem);
                inputEle.value = existingTags.join(', ') + ', ';

                tagSuggestionsEle.innerHTML = '';
                suggestionItemsIndex = 0;

            }

        }

        function updateActiveTagSuggestion() {

            for (let i = 0; i < suggestionItemsCount; i++) {
                suggestionItems[i].classList.toggle('active', i === suggestionItemsIndex);
            }

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


