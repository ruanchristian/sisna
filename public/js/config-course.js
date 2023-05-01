$(document).on('click', '.edit-course', function(){
    const COURSE_ID = $(this).val();
    const URL = $(location).attr('href');

    let formUrl = $('#form-update').attr('action');

    $.get(URL + '/request-course/' + COURSE_ID, function(data) {
        $('#name-course').val(data.nome);
        $('#modal-course').modal('show');
        $('#form-update').attr('action', formUrl.replace(/edit-course\/\d+$/, "edit-course/" + COURSE_ID));
    });
});