$(document).ready(function() {
    function inputAvailable(sourceName = '') {
        $('#source-detailed').hide()
        if ((sourceName === 'INSTAGRAM') || (sourceName === 'FACEBOOK')) {
            $('#source-detailed').hide()
        } else {
            $('#source-detailed').show()

            if (sourceName == 'REFERANCE') {
                $('input[name="ref_name"]').parent().show()
                $('input[name="b2b_company_name"]').parent().hide()
            } else if (sourceName == 'B2B') {
                $('input[name="ref_name"]').parent().hide()
                $('input[name="b2b_company_name"]').parent().show()
            }
        }
    }

    // On state page
    let selectedText = $('select[name="source_name"] option:selected').text();
    inputAvailable(selectedText)

    if ($.empty(jsURI[3])) {
        $('#source-detailed').hide()
        $('select[name="source_name"]').on('change', function() {
            let selectedText = $('select[name="source_name"] option:selected').text();
            inputAvailable(selectedText)

            $.ajax({
                url: BASE_URL + 'leads_sources/generate_code',
                type: 'GET',
                async: true,
                dataType: 'json',
                data: {
                    source_id: $(this).val()
                },
                success: function(response) {
                    if (response.status) {
                        $('input[name="source_code"]').val(response.data.code_generated);
                    } else {
                        $('input[name="source_code"]').val('');
                    }
                },
                error: function() {
                    $('input[name="source_code"]').val('');
                }
            })
        })

        setInterval(() => {
            if (!$.empty($('input[name="source_code"]').val())) {
                $.ajax({
                    url: BASE_URL + 'leads_sources/update_booked_code',
                    type: 'GET',
                    async: true,
                    dataType: 'json',
                    data: {
                        source_code: $('input[name="source_code"]').val()
                    },
                    success: function(response) {
                        console.log(response.data.source_code)
                        if (response.status) {
                            $('input[name="source_code"]').val(response.data.source_code);
                        } else {
                            $('input[name="source_code"]').val('');
                        }
                    },
                    error: function() {
                        $('input[name="source_code"]').val('');
                    }
                })
            }
        }, 30000);
    }
})