$(document).ready(function() {
    $.select2('select[name="source_code"]');

    $('select[name="source_code"]').on('change', function() {
        let sourceCode = $(this).val();

        $.ajax({
            url: BASE_URL + 'leads_sources/source_information',
            type: 'GET',
            async: true,
            dataType: 'json',
            data: {
                source_code: sourceCode
            },
            success: function(response) {
                if (response.status) {
                    if (!$.empty(response.data)) {
                        if (response.data.source_name == 'OTHER') {
                            $('textarea[name="source_information"]').attr('readonly', false);
                        } else {
                            $('textarea[name="source_information"]').attr('readonly', true);
                            $('textarea[name="source_information"]').val(response.data.source_information)
                        }
                    }
                }
            }
        })
    })
})