$(document).ready(function() {
    var queryMandatory = [
        $('input[name="student_number"]'),
        $('input[name="date_of_birth"]'),
        $('input[name="religion"]'),
        $('input[name="gender"]'),
        $('input[name="aadhaar_number"]'),
        $('input[name="father_name"]'),
        $('input[name="mother_name"]'),
        $('input[name="final_fees"]'),
        $('select[name="country_id"]'),
        $('select[name="state_id"]'),
        $('select[name="university_id"]'),
        $('select[name="course_id"]'),
        $('input[name="session"]'),
        $('input[name="invoice_number"]'),
        $('input[name="total_amount"]'),
        $('input[name="discount_percent"]'),
        $('input[name="final_amount"]'),
        $('input[name="advance_amount"]'),
        $('input[name="advance_percent"]'),
        $('input[name="remaining_balance"]'),
        $('input[name="due_date"]')
    ];
    var baseLabel = [];

    let status = $('select[name="status"]').val()
    setMandatory(status)

    function setMandatory(status) {
        if (status == 'YES') {
            // Loop for set mandatory input
            $.each(queryMandatory, function(key, value) {
                let $label = $('#lbl-' + value.attr('name'));
                let label = $label.text()

                if (baseLabel[key] !== undefined) {
                    label = baseLabel[key]
                } else {
                    baseLabel[key] = label
                }

                value.attr('required', true)
                $label.html(label + ' <label class="text-danger mb-0">*</label>')
            })

            studentInformationCollapse(collapse = 'show')
            moreInformationCollapse(collapse = 'show')
        } else {
            // Loop for remove mandatory input
            $.each(queryMandatory, function(key, value) {
                let $label = $('#lbl-' + value.attr('name'));

                value.attr('required', false)
                $label.html(baseLabel[key])
            })

            if (status === 'NO') {
                studentInformationCollapse('hide')
                moreInformationCollapse('hide')
            }
        }
    }

    function onChangeStatus() {
        let status = $('select[name="status"]').val()
        let courseID = $('select[name="course_id"]').val()
        let sourceCode = $('select[name="source_code"]').val()
        let studentNumber = $('input[name="student_number"]').val()
        let invoiceNumber = $('input[name="invoice_number"]').val()
        let tax_percent = $('input[name="tax_percent"]').val()
        let advancePercent = $('input[name="advance_percent"]').val()
        let additionalCertificateFee = $('input[name="additional_certificate_fee"]').val()

        if (status === 'YES') {

            $.ajax({
                url: BASE_URL + 'leads/is_status_yes',
                type: 'GET',
                async: true,
                dataType: 'json',
                data: {
                    course_id: courseID,
                    source_code: sourceCode,
                    tax_percent: tax_percent,
                    advance_percent: advancePercent,
                    additional_certificate_fee: additionalCertificateFee
                },
                beforeSend: function() {
                    $.loader('show')
                },
                success: function(response) {
                    if (response.status) {
                        $('input[name="final_fees"]').val(response.data.final_amount)
                        $('input[name="total_amount"]').val(response.data.total_amount)
                        $('input[name="discount_percent"]').val(response.data.discount_percent)
                        $('input[name="final_amount"]').val(response.data.final_amount)
                        $('input[name="advance_amount"]').val(response.data.advance_amount)
                        $('input[name="remaining_balance"]').val(response.data.remaining_balance)
                    } else {
                        $('input[name="final_fees"]').val('')
                        $('input[name="total_amount"]').val('')
                        $('input[name="discount_percent"]').val('')
                        $('input[name="final_amount"]').val('')
                        $('input[name="advance_amount"]').val('')
                        $('input[name="remaining_balance"]').val('')
                    }
                },
                error: function() {
                    $('input[name="final_fees"]').val('')
                    $('input[name="total_amount"]').val('')
                    $('input[name="discount_percent"]').val('')
                    $('input[name="final_amount"]').val('')
                    $('input[name="advance_amount"]').val('')
                    $('input[name="remaining_balance"]').val('')
                }
            })

            /* =============== Booked student number =============== */
            if (studentNumber == '') {
                // Create student number
                $.ajax({
                    url: BASE_URL + 'students/generate_number',
                    type: 'GET',
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('input[name="student_number"]').val(response.data.number)
                        } else {
                            $('input[name="student_number"]').val('')
                        }
                    },
                    error: function() {
                        $('input[name="student_number"]').val('')
                    }
                })

                //Update student booked number
                clearInterval(updateStudentNumber)
                var updateStudentNumber = setInterval(() => {
                    let studentNumber = $('input[name="student_number"]').val()
                    if (studentNumber != '') {
                        $.ajax({
                            url: BASE_URL + 'students/update_booked_number',
                            type: 'GET',
                            async: true,
                            dataType: 'json',
                            data: {
                                number: studentNumber
                            },
                            success: function(response) {
                                if (response.status) {
                                    $('input[name="student_number"]').val(response.data.number)
                                } else {
                                    $('input[name="student_number"]').val('')
                                }
                            },
                            error: function() {
                                $('input[name="student_number"]').val('')
                            }
                        })
                    } else {
                        clearInterval(updateStudentNumber)
                    }
                }, 3000);
            }
            /* ===================================================== */

            /* =============== Booked invoice number =============== */
            if (invoiceNumber == '') {
                // Create invoice number
                $.ajax({
                    url: BASE_URL + 'payment_invoices/generate_number',
                    type: 'GET',
                    async: true,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('input[name="invoice_number"]').val(response.data.number)
                        } else {
                            $('input[name="invoice_number"]').val('')
                        }
                    },
                    error: function() {
                        $('input[name="invoice_number"]').val('')
                    }
                })

                //Update invoice booked number
                clearInterval(updateInvoiceNumber)
                var updateInvoiceNumber = setInterval(() => {
                    let invoiceNumber = $('input[name="invoice_number"]').val()
                    if (invoiceNumber != '') {
                        $.ajax({
                            url: BASE_URL + 'payment_invoices/update_booked_number',
                            type: 'GET',
                            async: true,
                            dataType: 'json',
                            data: {
                                number: invoiceNumber
                            },
                            success: function(response) {
                                if (response.status) {
                                    $('input[name="invoice_number"]').val(response.data.number)
                                } else {
                                    $('input[name="invoice_number"]').val('')
                                }
                            },
                            error: function() {
                                $('input[name="invoice_number"]').val('')
                            }
                        })
                    } else {
                        clearInterval(updateInvoiceNumber)
                    }
                }, 3000);
            }
            /* ===================================================== */
        } else {
            $('input[name="student_number"]').val('')
            $('input[name="final_fees"]').val('')
            $('input[name="total_amount"]').val('')
            $('input[name="discount_percent"]').val('')
            $('input[name="advance_amount"]').val('')
            $('input[name="final_amount"]').val('')
            $('input[name="remaining_balance"]').val('')
        }

        setMandatory(status)
        $.loader('hide')
    }

    $('input[name="tax_percent"], input[name="advance_percent"], input[name="additional_certificate_fee"]').on('input', function() {
        onChangeStatus()
    })

    $('select[name="status"], select[name="course_id"], select[name="source_code"]').on('change', function() {
        onChangeStatus()
    })

    setTimeout(() => {
        moreInformationCollapse()
        studentInformationCollapse()
    }, 500);

    function moreInformationCollapse(collapse = '') {
        $moreInformation = $('#more_information');

        if (collapse == 'show') {
            $moreInformation.addClass('show');
        } else if (collapse != 'hide') {
            $moreInformation.removeClass('show');
        } else {
            if ($moreInformation.hasClass('show')) {
                $moreInformation.removeClass('show');
            } else {
                $moreInformation.addClass('show');
            }
        }
    }

    function studentInformationCollapse(collapse = '') {
        $studentInformation = $('#student_information');

        if (collapse == 'show') {
            $studentInformation.addClass('show');
        } else if (collapse == 'hide') {
            $studentInformation.removeClass('show');
        } else {
            if ($studentInformation.hasClass('show')) {
                $studentInformation.removeClass('show');
            } else {
                $studentInformation.addClass('show');
            }
        }
    }
})