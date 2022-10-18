$(function () {

    $('[data-form-control-tags=1]').amsifySuggestags({
        suggestionsAction: {
            timeout: -1,
            minChars: 1,
            minChange: -1,
            delay: 100,
            type: 'GET',
            url: '/admin/data/tags/suggest',
        }
    });
})