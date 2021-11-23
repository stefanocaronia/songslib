import 'chosen-js';
import 'autocomplete.js/dist/autocomplete.jquery';

const $form = $('#song-form');

$(function() {
    inizializeFormChangeEvents();
    initializeCollectionOperations(document.body);
    initializeChosen(document.body);
    initializeAutocomplete(document.body);
})

function inizializeFormChangeEvents() {
    $('input[name="song[single]"]').on('change', function() {
        const single = ($(this).is(':checked') ? 1 : 0);
        const data = {}
        if (single) {
            data.single = 1;
        }
        $.ajax({
            url: $form.attr('action'),
            type: getFormMethod($form),
            data: data,
            success: function(html) {
                const $albumsList = $(html).find('.albums-list-container');
                $('.albums-list-container').replaceWith($albumsList);
                initializeAutocomplete($('.albums-list-container')[0]);
            }
        });
    });
}

function initializeCollectionOperations() {
    $('.action-add').on('click', e => {
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

    $('.collection').on('click', '.action-delete', function(e) {
        const $button = $(this);
        e.preventDefault();

        $button.parents('.collection-field').fadeOut(function() {
            $(this).remove();
        });

        return false;
    });
}

function initializeChosen(container) {
    $(container).find('select:not(".processed")').chosen({
        disable_search_threshold: 10,
        no_results_text: 'Oops, nothing found!',
        width: '100%'
    }).addClass('processed');
}

function initializeAutocomplete(container) {
    $(container).find('input.autocomplete-input:not(".processed")').each(function() {
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
        });

        $input.on('blur', function() {
            $original.val($input.val()).trigger('change');
        })
        $input.addClass('processed');
    });
}

function getFormMethod($form) {
    // when the form method is different from 'POST', Symfony adds a new field with the name '_method'
    // to the form and sets the form method as its value
    if ($form.find('input[name=_method]').length) {
        return $form.find('input[name=_method]').val();
    }

    return $form.attr('method')
}
