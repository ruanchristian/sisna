$(document).ready(() => {
    $('#users-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            searchPlaceholder: "Buscar usuário...",
            info: "Mostrando de _START_ até _END_ de _TOTAL_ usuários",
            lengthMenu: "Exibir _MENU_ usuários por página"
        },
        columnDefs: [
            {orderable: false, targets: [2, 3, 4]}
        ],
    });
})