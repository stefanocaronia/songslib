$(function() {
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
    });

    $('.collection').on('click', '.action-delete', e => {
        const button = $(e.currentTarget);
        e.preventDefault();

        button.parents('.collection-field').fadeOut(function() {
            $(this).remove();
        });
    });
})