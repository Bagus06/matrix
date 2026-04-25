$(document).ready(function () {
    let $tbFeature = $('#table-feature');
    let $btnAddFeature = $('#btnAddFeature');
    let $btnGenerateDefaultFeature = $('#btnGenerateDefaultFeature');

    let lastNumRows = function () {
        let lastNumRow = $tbFeature.find('tbody tr:last').data('numrow')
        if (lastNumRow === undefined) {
            lastNumRow = '';
        }

        return lastNumRow;
    }

    addRow(lastNumRows(), jsURI[3])

    function addRow(lastNumRows, module_id = null, defaultFeature = false) {
        setTimeout(() => {
            $.ajax({
                url: BASE_URL + 'apps_module_features/tb_feature',
                type: 'GET',
                async: true,
                data: {
                    last_num_row: lastNumRows,
                    module_id: module_id,
                    default_feature: defaultFeature
                },
                beforeSend: function () {
                    $.loader('show');
                },
                success: function (response) {
                    if (lastNumRows === '') {
                        $tbFeature.find('tbody').html(response)
                    } else {
                        $tbFeature.find('tbody').append(response)
                    }

                    if (defaultFeature) {
                        let values = [];
                        $tbFeature.find('tbody input[name="ft_feature_code[]"]').each(function () {
                            let val = $(this).val().trim();

                            if (val !== "") {
                                if (values.includes(val)) {
                                    $(this).parent().parent().parent().remove()
                                } else {
                                    values.push(val);
                                }
                            }
                        });
                    }
                    $.loader('hide');
                },
                error: function () {
                    $.loader('hide');
                }
            })

        }, 5);
    }

    $btnAddFeature.on('click', function (e) {
        e.preventDefault();

        addRow(lastNumRows())
    })

    $btnGenerateDefaultFeature.on('click', function (e) {
        e.preventDefault();

        addRow(lastNumRows(), '', true)
    })

    $tbFeature.on('click', '.btnDeleteFeature', function () {
        let $row = $(this).parent().parent();
        let $inputDelete = $row.find('input[name*="ft_delete"]');
        let deleteVal = $inputDelete.val()

        function removeRequired(requiredVal = true) {
            $row.find('input').each(function (e) {
                let required = $(this).is('[required]')

                if (required) {
                    $(this).prop('required', false);
                    $(this).attr('data-required', true);
                    $(this).data('required', true);
                } else {
                    if ($(this).data('required')) {
                        $(this).prop('required', true);
                        $(this).removeAttr('data-required');
                    }
                }
            })
        }

        if ($.empty(deleteVal) || (deleteVal == 0)) {
            $inputDelete.val(1)
        } else {
            $inputDelete.val(0)
        }

        if ($inputDelete.val() == 1) {
            if (!$row.hasClass('strikeout')) {
                $row.addClass('strikeout')
            }

            removeRequired(false)
            $(this).find('i').removeClass('fa-trash')
            $(this).find('i').addClass('fa-trash-restore')
        } else {
            if ($row.hasClass('strikeout')) {
                $row.removeClass('strikeout')
            }

            removeRequired()
            $(this).find('i').removeClass('fa-trash-restore')
            $(this).find('i').addClass('fa-trash')
        }
    })
})