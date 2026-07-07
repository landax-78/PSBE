document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(function (button) {
        button.addEventListener('click', function (event) {
            if (!confirm('Yakin ingin menghapus data ini?')) {
                event.preventDefault();
            }
        });
    });

    const table = document.querySelector('#detail-table tbody');
    const addButton = document.querySelector('#add-row');

    if (table && addButton) {
        addButton.addEventListener('click', function () {
            const firstRow = table.querySelector('tr');
            const clone = firstRow.cloneNode(true);
            clone.querySelectorAll('select, input').forEach(function (field) {
                field.value = '';
            });
            table.appendChild(clone);
        });

        table.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.remove-row');
            if (removeButton) {
                if (table.querySelectorAll('tr').length > 1) {
                    removeButton.closest('tr').remove();
                } else {
                    alert('Minimal harus ada 1 detail layanan.');
                }
            }
        });
    }
});
