const changeState = (checkbox, p, id) => {
    let state = document.getElementById(p);

    update(checkbox.value, checkbox.checked);

    if (checkbox.checked) {
        $('.drop-d'+p).prop('disabled', false);
        $('.res-'+p).attr('href', '#');
        $('#ver-'+p).prop('disabled', true);
        state.innerText = "Em andamento"
    } else {
        $('.drop-d'+p).prop('disabled', true);
        $('.res-'+p).attr('href', 'results/'+id);
        $('#ver-'+p).prop('disabled', false);
        state.innerText = "Encerrado";
    }
};

function update(id, stateValue) {
    $.ajax({
        url: $(location).attr("origin") + "/processes/change-state/" + id,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "PUT",
            estado: stateValue ? 1 : 0,
        }
    }).done((msg) => {
         Swal.fire({
             title: 'Processo alterado!',
             html: msg.ok,
             icon: 'success',
             confirmButtonColor: '#3c6cac'
         });
    }).fail(() => {
         Swal.fire({
             title: 'Erro inesperado!',
             text: 'Não foi possível editar esse processo.',
             icon: 'warning',
             confirmButtonColor: '#3c6cac'
         }).then(() => {location.reload()});
    });
}