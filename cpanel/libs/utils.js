function addMessage(status, message) {
    msg = $('<div class="alert alert-dismissible alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' + message + '</div>');
    $('#flash').append(msg);

    $('#flash .alert').delay(5000).fadeOut(300, function () {
        $(this).remove();
    });
}


function add_ajax_loading() {
    msg = $('<div id="overlay"><img src="' + baseurl + 'img/ajax-loader.gif" /></div>');
    $('body').append(msg);
}

function delete_ajax_loading() {
    $('body > #overlay').remove();
}