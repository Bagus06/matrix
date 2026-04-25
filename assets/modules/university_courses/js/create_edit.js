$(document).ready(function() {
    $.select2('select[name="university_id"]')
    $('input[name="discount_date_periode"]').daterangepicker({
        autoUpdateInput: false, // Prevents auto-filling the input on load
        locale: {
            cancelLabel: 'Clear' // Adds a "Clear" button to the picker
        }
    });

    // Event handler for when a date range is applied
    $('input[name="discount_date_periode"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    // Event handler for when the "Clear" button is clicked
    $('input[name="discount_date_periode"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('input[name="fee"], input[name="markup_fee_percent"]').on('input', function() {
        let dryFee = $('input[name="fee"]').val();
        let markup = $('input[name="markup_fee_percent"]').val();
        $.ajax({
            url: BASE_URL + 'university_courses/count_final_fee',
            type: 'GET',
            async: true,
            dataType: 'json',
            data: {
                dry_fee: dryFee,
                markup: markup
            },
            success: function(response) {
                if (response.status) {
                    $('input[name="final_fee"]').val(response.data.final_fee);
                } else {
                    $('input[name="final_fee"]').val(0);
                }
            },
            error: function() {
                $('input[name="final_fee"]').val(0);
            }
        })
    })
})