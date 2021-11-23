export function initializeAutocomplete(container) {
    $(container).find('input.autocomplete-input:not(.processed)').each(function() {
        const $input = $(this);
        const $container = $input.closest('div.entity-autocomplete');
        const $original = $($container.find('.entity-autocomplete-value input')[0]);
        const autocompleteUrl = $input.data('autocompleteUrl');
        const autocompleteField = $input.data('autocompleteField');
        const params = $input.data('autocompleteParams');

        $input.autocomplete({
            hint: false,
            minLength: 0,
            autoSelect: true,
            openOnFocus: true,
            tabAutocomplete: true,
        }, [
            {
                source: function(query, callback) {
                    $.ajax({
                        url: autocompleteUrl + '?q=' + query,
                        data: params
                    }).then(function(data) {
                        callback(data.items);
                    });
                },
                displayKey: autocompleteField,
                debounce: 300
            }
        ]).on('autocomplete:selected', function(event, suggestion, dataset, context) {
            $original.val(suggestion.name).trigger('change');
        }).on('blur', function() {
            $original.val($input.val()).trigger('change');
        }).addClass('processed');
    });
}

export function initializeCollectionOperations(container) {
    $(container).off('click', '.action-add').on('click', '.action-add', e => {
        const button = $(e.currentTarget);
        const list = $(button.data('target'));

        let nextIndex = 0;
        let tableBody = $(list).find('tbody');
        if (tableBody.children().length > 0) {
            nextIndex = parseInt(tableBody.children().last().attr('rel')) + 1;
        }

        const prototype = list.data('prototype').replace(/__name__/g, nextIndex);
        $(prototype).appendTo(tableBody);
        initializeChosen(list);
        initializeAutocomplete(list);
    });

    $(container).find('.collection').off('click', '.action-delete').on('click', '.action-delete', function(e) {
        const $button = $(this);
        e.preventDefault();

        $button.parents('.collection-field').fadeOut(function() {
            $(this).remove();
        });

        return false;
    });
}

export function initializeChosen(container) {
    $(container).find('select:not(.processed)').chosen({
        disable_search_threshold: 10,
        no_results_text: 'Oops, nothing found!',
        width: '100%'
    }).addClass('processed');
}
