const changeState = (checkbox, p) => {
    let state = document.getElementById(p);

    update(checkbox.value, checkbox.checked);

    if (checkbox.checked) {
        state.innerText = "Aberto";
    } else {
        state.innerText = "Fechado";
    }
};

function update(id, state) {
    $.ajax({
        url: $(location).attr("href") + "/change-state/" + id,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "PUT",
            estado: state ? 1 : 0,
        },
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
