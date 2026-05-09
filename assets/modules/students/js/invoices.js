$(document).ready(function() {
    var btnAdvanceInvoice = $('#btn-advence-invoice');
    var modalAdvanceINvoice = $('#modal-advance-invoice');

    /* ====================== Detect changes to forms ====================== */
    let initialPaymentInformation = {};
    let changePaymentInformation = false;

    // Normalize value (number-safe)
    function normalizeValue(value) {
        if (value === null || value === undefined || value === '') return 0;

        value = value.toString().trim();

        // Remove thousand separator
        value = value.replace(/\./g, '');

        // Replace decimal comma
        value = value.replace(',', '.');

        let num = Number(value);

        return isNaN(num) ? value : num;
    }

    // Get all form values (single source of truth)
    function getAllFormValues() {
        let data = {};

        $('#section-payment-information')
            .find('input, select, textarea')
            .each(function() {
                let $el = $(this);
                let name = $el.attr('name') || $el.attr('id');
                if (!name) return;

                let type = $el.attr('type');

                // Radio (only once per group)
                if (type === 'radio') {
                    if (data[name] !== undefined) return;

                    let val = $('input[name="' + name + '"]:checked').val();
                    data[name] = normalizeValue(val);
                    return;
                }

                // Checkbox
                if (type === 'checkbox') {
                    data[name] = $el.is(':checked') ? 1 : 0;
                    return;
                }

                // Default input/select/textarea
                data[name] = normalizeValue($el.val());
            });

        return data;
    }

    // Compare two objects (smart compare)
    function isEqual(obj1, obj2) {
        let keys = Object.keys(obj1);

        for (let key of keys) {
            let val1 = obj1[key];
            let val2 = obj2[key];

            // If both are numbers → strict compare
            if (typeof val1 === 'number' && typeof val2 === 'number') {
                if (val1 !== val2) return false;
            } else {
                // Otherwise compare as string
                if ((val1 !== null && val1 !== undefined ? val1 : '') !==
                    (val2 !== null && val2 !== undefined ? val2 : '')) return false;
            }
        }

        return true;
    }

    // Store initial state
    function initPaymentDefaultValue() {
        initialPaymentInformation = getAllFormValues();
    }

    // Check changes
    function checkPaymentChanges() {
        let currentData = getAllFormValues();

        let isChanged = !isEqual(currentData, initialPaymentInformation);

        changePaymentInformation = isChanged;

        return isChanged;
    }

    // Listener
    function initPaymentInformationListener() {
        $('#section-payment-information').on('input change', 'input, select, textarea', function() {
            checkPaymentChanges();
        });
    }

    // Init
    initPaymentDefaultValue();
    initPaymentInformationListener();
    /* ===================================================================== */

    function onChangePayment() {
        let courseID = $('select[name="course_id"]').val()
        let sourceCode = $('select[name="source_code"]').val()
        let taxPercent = $('input[name="tax_percent"]').val()
        let aditionalDiscountPercent = $('input[name="aditional_discount_percent"]').val()
        let advancePercent = $('input[name="advance_percent"]').val()
        let additionalCertificateFee = $('input[name="additional_certificate_fee"]').val()

        $.ajax({
            url: BASE_URL + 'payment_invoices/change_payment',
            type: 'GET',
            async: true,
            dataType: 'json',
            data: {
                course_id: courseID,
                source_code: sourceCode,
                tax_percent: taxPercent,
                aditional_discount_percent: aditionalDiscountPercent,
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
                    $('input[name="total_discount_percent"]').val(response.data.total_discount_percent)
                    $('input[name="remaining_balance"]').val(response.data.remaining_balance)
                    $('input[name="advance_amount"]').val(response.data.advance_amount)
                    $('input[name="final_amount"]').val(response.data.final_amount)
                } else {
                    $('input[name="final_fees"]').val('')
                    $('input[name="total_amount"]').val('')
                    $('input[name="discount_percent"]').val('')
                    $('input[name="total_discount_percent"]').val('')
                    $('input[name="remaining_balance"]').val('')
                    $('input[name="advance_amount"]').val('')
                    $('input[name="final_amount"]').val('')
                }
            },
            error: function() {
                $('input[name="final_fees"]').val('')
                $('input[name="total_amount"]').val('')
                $('input[name="discount_percent"]').val('')
                $('input[name="total_discount_percent"]').val('')
                $('input[name="remaining_balance"]').val('')
                $('input[name="advance_amount"]').val('')
                $('input[name="final_amount"]').val('')
            }
        })
        $.loader('hide')
    }

    $('input[name="tax_percent"], input[name="advance_percent"], input[name="aditional_discount_percent"], input[name="additional_certificate_fee"]').on('input', function() {
        onChangePayment()
    })

    $('select[name="status"], select[name="course_id"], select[name="source_code"]').on('change', function() {
        onChangePayment()
    })

    function generateInvoice(studentNumber) {
        const url = BASE_URL + `payment_invoices/generate_invoice/?student_number=` + bin2hex(studentNumber);
        window.open(url, '_blank', 'width=900,height=600');
        // $.ajax({
        //     url: BASE_URL + 'payment_invoices/generate_invoice',
        //     type: 'GET',
        //     async: true,
        //     dataType: 'json',
        //     beforeSend: function() {
        //         $.loader('show')
        //     },
        //     data: {
        //         student_number: studentNumber
        //     },
        //     success: function(response) {
        //         if (response.status) {
        //             $.downloads('payment_invoices/downloads', response.data.file_directory, response.data.filename)
        //         } else {
        //             let errInfo = $.getErrorInfo(response.code)

        //             if (!$.empty(errInfo)) {
        //                 $.invyAlert({
        //                     title: errInfo.code,
        //                     text: errInfo.message,
        //                     icon: errInfo.level,
        //                     cabtn: errInfo.cabtn,
        //                     catext: errInfo.catext
        //                 })
        //             }
        //         }

        //         $.loader('hide')
        //     },
        //     error: function(ressponse) {
        //         $.loader('hide')
        //     }
        // })
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
                        if (changePaymentInformation) {
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