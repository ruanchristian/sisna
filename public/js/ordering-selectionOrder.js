$(() => {
    $('#sortable').sortable({
        animation: 150,
        onUpdate: function() {
            $('#sortable tr').each(function (index) {
                $(this).find('td:first-child').text(index + 1 + "º");
            })
            updateOrder();
        },
    });

    function updateOrder() {
        const rows = Array.from(document.querySelectorAll('#sortable tr'));
        const data = rows.map((row) => {
            const [posicao, criterio, ordenacao] = Array.from(row.querySelectorAll('td'));
            return {
                [ordenacao.querySelector('select').getAttribute('id')]: ordenacao.querySelector('option:checked').value,
            };
        });

        const order = data.reduce((acc, cur) => Object.assign(acc, cur), {});
        $('#order-selection').val(JSON.stringify(order));
    }

    const selects = document.querySelectorAll('select');
    selects.forEach((select) => {
        select.addEventListener('change', () => {
            $('#sortable').sortable('refresh');
            updateOrder();
        });
    });

    $('#submit-modal').click(function (e) {
        e.preventDefault();

        let passInput = $('#pass').val();

        $.ajax({
            url: $(location).attr('origin') + '/users/checkpass',
            type: "POST",
            data: {
                senha: passInput,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(ans) {
                Swal.fire({
                    icon: 'success',
                    title: ans.success,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000,
                });

                setTimeout(() => {$('#configs').submit()}, 2000);
            },
            error: function(error) {
                $('#pass').val("");

                Swal.fire({
                    icon: 'error',
                    title: error.responseJSON.fail,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                })
            }
        });
    });
});