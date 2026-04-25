$(document).ready(function() {
    var btnAdvanceInvoice = $('#btn-advence-invoice');
    var modalAdvanceINvoice = $('#modal-advance-invoice');

    /* ====================== Detect changes to forms ====================== */
    let formChanged = false;

    $('form').on('input change', 'input, select, textarea', function() {
        formChanged = true;
    });
    /* ===================================================================== */

    function generateInvoice(studentNumber) {
        $.ajax({
            url: BASE_URL + 'payment_invoices/generate_invoice',
            type: 'GET',
            async: true,
            dataType: 'json',
            beforeSend: function() {
                $.loader('show')
            },
            data: {
                student_number: studentNumber
            },
            success: function(response) {
                if (response.status) {
                    $.downloads('payment_invoices/downloads', response.data.file_directory, response.data.filename)
                } else {
                    let errInfo = $.getErrorInfo(response.code)

                    if (!$.empty(errInfo)) {
                        $.invyAlert({
                            title: errInfo.code,
                            text: errInfo.message,
                            icon: errInfo.level,
                            cabtn: errInfo.cabtn,
                            catext: errInfo.catext
                        })
                    }
                }

                $.loader('hide')
            },
            error: function(ressponse) {
                $.loader('hide')
            }
        })
    }

    function releaseInvoice(data) {
        $.ajax({
            url: BASE_URL + 'payment_invoices/release_invoice',
            type: 'POST',
            async: true,
            dataType: 'json',
            data: data,
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                if (response.status) {
                    $.invyAlert({
                        title: 'INVOICE',
                        text: 'The invoice has been successfully released. Please download the invoice to send it to the student concerned.',
                        icon: 'success',
                        cobtn: true,
                        cotext: 'OK',
                        alertResponse: function(response) {
                            if (response.alertResponse.isConfirmed) {
                                location.reload();
                            }
                        }
                    })
                } else {
                    $.invyAlert({
                        title: 'INVOICE',
                        text: 'Sorry, the invoice failed to be released. Please try again later, or report the problem to an administrator.',
                        icon: 'error',
                        cobtn: false,
                    })
                }
                $.loader('hide')
            },
            error: function(response) {
                $.loader('hide')
            }
        })
    }

    function onChangePayment() {
        let courseID = $('select[name="course_id"]').val()
        let sourceCode = $('select[name="source_code"]').val()
        let tax_percent = $('input[name="tax_percent"]').val()
        let advancePercent = $('input[name="advance_percent"]').val()

        $.ajax({
            url: BASE_URL + 'payment_invoices/change_payment',
            type: 'GET',
            async: true,
            dataType: 'json',
            data: {
                course_id: courseID,
                source_code: sourceCode,
                tax_percent: tax_percent,
                advance_percent: advancePercent
            },
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                if (response.status) {
                    $('input[name="total_amount"]').val(response.data.total_amount)
                    $('input[name="discount_percent"]').val(response.data.discount_percent)
                    $('input[name="advance_amount"]').val(response.data.advance_amount)
                    $('input[name="final_amount"]').val(response.data.final_amount)
                    $('input[name="remaining_balance"]').val(response.data.remaining_balance)
                    $('input[name="final_payment"]').val(response.data.final_payment)
                } else {
                    $('input[name="total_amount"]').val('')
                    $('input[name="discount_percent"]').val('')
                    $('input[name="advance_amount"]').val('')
                    $('input[name="final_amount"]').val('')
                    $('input[name="remaining_balance"]').val('')
                    $('input[name="final_payment"]').val('')
                }
            },
            error: function() {
                $('input[name="total_amount"]').val('')
                $('input[name="discount_percent"]').val('')
                $('input[name="advance_amount"]').val('')
                $('input[name="final_amount"]').val('')
                $('input[name="remaining_balance"]').val('')
                $('input[name="final_payment"]').val('')
            }
        })
        $.loader('hide')
    }

    $('input[name="tax_percent"], input[name="advance_percent"]').on('input', function() {
        onChangePayment()
    })

    $('select[name="status"], select[name="course_id"], select[name="source_code"]').on('change', function() {
        onChangePayment()
    })

    btnAdvanceInvoice.on('click', function() {
        let invoiceStatus = $(this).data('invoicestatus')
        let invoiceNumber = $('input[name="invoice_number"]').val()
        let studentNumber = $('input[name="student_number"]').val()
        let paymentDiscount = $('input[name="discount_percent"]').val()
        let paymentTAX = $('input[name="tax_percent"]').val()
        let paymentAdvancePercent = $('input[name="advance_percent"]').val()
        let dueDate = $('input[name="due_date"]').val()

        let data = {
            student_number: studentNumber,
            invoice_number: invoiceNumber,
            discount_percent: paymentDiscount,
            tax_percent: paymentTAX,
            advance_percent: paymentAdvancePercent,
            due_date: dueDate
        };

        if (invoiceStatus === 'APPROVED') {
            let studentNumber = $('input[name="student_number"]').val();
            generateInvoice(studentNumber)
        } else {
            $.invyAlert({
                title: 'Invoices',
                text: 'If there are changes to the Discount, TAX, or Advance Percent, please change them first before issuing the invoice. Are you sure you want to issue this invoice?',
                icon: 'question',
                cabtn: true,
                catext: 'Cancel',
                cobtn: true,
                cotext: 'Confirm',
                alertResponse: function(response) {
                    if (response.alertResponse.isConfirmed) {
                        if (formChanged) {
                            $.invyAlert({
                                title: 'FORM',
                                text: 'It seems there are changes to the student data. Please save the form first so that the changes are not lost.',
                                icon: 'warning',
                                cobtn: true,
                                cotext: 'OK',
                            })
                        } else {
                            releaseInvoice(data)
                        }
                    }
                }
            })
        }
    })
})