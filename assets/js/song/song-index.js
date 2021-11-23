$('button.action-delete-song').on('click', e => {
    const url = $(e.currentTarget).data('url');
    const confirmMessage = $(e.currentTarget).data('confirmMessage');

    console.log(url, confirmMessage);
    if (confirm(confirmMessage)) {
        $.ajax({
            url,
            method: 'DELETE',
            success: response => {
                if (response.success) {
                    window.location.reload();
                }
            }
        });
    }
});