import 'chosen-js';
import 'autocomplete.js/dist/autocomplete.jquery';
import {initializeChosen, initializeAutocomplete, initializeCollectionOperations} from '../components/initialize-plugins'

const form = 'form[name=song]';
const albumsList = '.albums-list-container';

inizializeFormChangeEvents();
initializeCollectionOperations($(form)[0]);
initializeChosen($(form)[0]);
initializeAutocomplete($(form)[0]);

function inizializeFormChangeEvents() {
    $('input[name="song[single]"]').on('change', function() {
        const single = ($(this).is(':checked') ? 1 : 0);
        const data = {
            'song[title]': $('input[name="song[title]"]').val(),
            'song[_token]': $('input[name="song[_token]"]').val()
        }
        if (single) {
            data['song[single]'] = 1;
        }
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: data,
            success: function(html) {
                const $albumsList = $(html).find(albumsList);
                $(albumsList).replaceWith($albumsList);
                initializeCollectionOperations($albumsList[0]);
                initializeAutocomplete($albumsList[0]);
            }
        });
    });
}

