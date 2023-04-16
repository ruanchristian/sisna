const URL = $(location).attr('href');

// Montando modal do user com AJAX
$(document).on('click', '.edit-user', function(){
    const USER_ID = $(this).val();
    let formUrl = $('#form-update').attr('action');

    $.get(URL + '/request/' + USER_ID, function(data) {
        $('#name-user').val(data.name);
        $('#email').val(data.email);
        $('#type-user').val(data.type);
        $('#modalEdit').modal('show');
        $('#form-update').attr('action', formUrl.replace(/edit\/\d+$/, "edit/" + USER_ID));
    });
});

//Deletando user
$(document).on('click', '.delete-user', function() {
    const id = $(this).val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    Swal.fire({
        title: 'Você tem certeza disso?',
        text: "Este usuário não terá mais acesso ao sistema!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3c6cac',
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then((result) => {
         if (result.isConfirmed) {
            $.ajax({
                url: URL + '/delete/' + id,
                type: 'POST',
                data: {
                    '_method': 'DELETE',
                    "id": id,
                }
            }).done(() => {
                Swal.fire('Exclusão feita!', 'Esse usuário foi excluído com sucesso.', 'success').then(() => {
                    location.reload();
                })
            }).fail(() => {
                Swal.fire('Erro', 'Erro ao excluir esse user.', 'error');
            });
         }
      });
});