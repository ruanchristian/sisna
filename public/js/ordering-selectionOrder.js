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
});