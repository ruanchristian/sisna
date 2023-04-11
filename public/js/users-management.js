// Montando modal com AJAX
$(document).on('click', '.edit-user', function(){
    const USER_ID = $(this).val();
    const URL = $(location).attr('href');
    let formUrl = $('#form-update').attr('action');

    $.get(URL + '/request/' + USER_ID, function(data) {
        $('#name-user').val(data.name);
        $('#email').val(data.email);
        $('#type-user').val(data.type);
        $('#modalEdit').modal('show');
        $('#form-update').attr('action', formUrl.replace(/edit\/\d+$/, "edit/" + USER_ID));
    });
});