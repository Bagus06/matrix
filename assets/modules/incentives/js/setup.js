(function ($) {
    'use strict';

    $('#add-incentive-slab').on('click', function () {
        var rows = $('#incentive-slabs-table tbody tr');
        var nextFrom = '';
        if (rows.length) {
            var lastTo = rows.last().find('[name="slab_to[]"]').val();
            nextFrom = lastTo !== '' ? Number(lastTo) + 1 : '';
        }
        rows.last().find('[name="slab_to[]"]').prop('required', true);
        $('#incentive-slabs-table tbody').append(
            '<tr>' +
            '<td><input type="number" class="form-control" name="slab_from[]" min="0" step="1" value="' + nextFrom + '" required></td>' +
            '<td><input type="number" class="form-control" name="slab_to[]" min="0" step="1" placeholder="No limit"></td>' +
            '<td><input type="number" class="form-control" name="slab_rate[]" min="0" step="0.01" required></td>' +
            '<td class="text-right"><button type="button" class="btn btn-link text-danger remove-incentive-slab"><i class="fas fa-trash"></i></button></td>' +
            '</tr>'
        );
    });

    $('#incentive-slabs-table').on('click', '.remove-incentive-slab', function () {
        if ($('#incentive-slabs-table tbody tr').length <= 1) return;
        $(this).closest('tr').remove();
        $('#incentive-slabs-table tbody tr').last().find('[name="slab_to[]"]').prop('required', false).val('');
    });
})(jQuery);
