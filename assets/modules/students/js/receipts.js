$(document).ready(function() {
    var btnReceipt = $('#btn-receipt')
    var btnReleaseReceipt = $('#btn-release-receipt')
    var btnPrintReceipt = $('#btn-print-receipt')
    var modalReceipt = $('#modal-receipt')
    var studentNumber = $('input[name="student_number"]').val();
    var $receiptOptions = $('select[name="receipt_options"]');
    var $receiptFor = $('select[name="receipt_for"]');
    var receiptForSilentChange = false;
    var $receiptInstallment = $('input[name="receipt_installment"]');
    $receiptInstallment.parent().parent().css('display', 'none');
    var $totalReceiptEmount = $('input[name="total_receipt_amount"]');
    var $totalEmount = $('input[name="receipt_total_amount"]');
    var $remainingBalance = $('input[name="receipt_remaining_balance"]');
    var $receiptPaymentMethod = $('select[name="receipt_method"]');
    var $receiptAmount = $('input[name="receipt_amount"]');
    var $receiptNote = $('textarea[name="receipt_note"]');
    var $receiptDate = $('input[name="receipt_date"]');
    var $viewReceiptOutstandingBalance = $('input[name="view_outstanding_balance"]')
    var $viewReceiptAmount = $('input[name="view_receipt_amount"]')
    var $viewReceiptRemainingBalance = $('input[name="view_remaining_balance"]')
    var $viewReceiptDate = $('input[name="view_receipt_date"]')

    function payment(studentNumber, receiptFor = '') {
        let output = false;
        if (!$.empty(studentNumber)) {
            $.ajax({
                url: BASE_URL + 'payments/detailed',
                type: 'GET',
                async: false,
                dataType: 'json',
                data: {
                    'whereclause': "student_number = '" + studentNumber + "'"
                },
                beforeSend: function() {
                    $.loader('show')
                },
                success: function name(response) {
                    if (response.status) {
                        if (receiptFor === 'down_payment') {
                            $totalEmount.val(response.data.final_amount)
                            $receiptAmount.val(response.data.advance_amount)
                        } else if (receiptFor === 'final_payment') {
                            $totalEmount.val(response.data.final_amount)
                            $receiptAmount.val(response.data.final_amount)
                        } else if (!$.empty(receiptFor)) {
                            $totalEmount.val(response.data.final_amount)
                        }

                        if ($.empty(receiptFor)) {
                            $totalEmount.val('')
                            $remainingBalance.val('')
                        } else {
                            $remainingBalance.val(response.data.remaining_balance)
                        }
                    } else {
                        $totalEmount.val('')
                        $remainingBalance.val('')
                        receiptForSilentChange = true;
                        $receiptFor.val('').trigger('change');

                        $.invyAlert({
                            title: 'RECEIPT',
                            text: 'Sorry, Student data could not be found. Please try again later, or report the problem to an administrator.',
                            icon: 'error',
                            cabtn: true,
                            catext: 'OK',
                        })
                    }
                }
            })
            $.loader('hide')
        } else {
            $.invyAlert({
                title: 'RECEIPT',
                text: 'Sorry, Student data could not be found. Please try again later, or report the problem to an administrator.',
                icon: 'error',
                cobtn: false,
            })
        }

        return output;
    }

    function onOpenModal(studentNumber) {
        $('select[name="receipt_options"]').html('')
        let output = 1;
        $.ajax({
            url: BASE_URL + 'payment_receipts/form_configuration',
            method: 'GET',
            async: true,
            dataType: 'json',
            data: {
                student_number: studentNumber
            },
            beforeSend: function() {
                $.loader('show');
            },
            success: function(response) {
                if (response.status) {
                    if (response.data.final_payment) {
                        $viewReceiptOutstandingBalance.val('')
                        $viewReceiptAmount.val('')
                        $viewReceiptRemainingBalance.val('')
                        $viewReceiptDate.val('')

                        btnPrintReceipt.data('receiptnumber', '')
                        btnPrintReceipt.data('studentnumber', '')
                        btnPrintReceipt.attr('disabled', true)

                        /* ====================== Switch to tab print receipts ====================== */
                        if ($('#receipt-form-tab').hasClass('active')) {
                            $('#receipt-form-tab').removeClass('active');
                            $('#receipt-form').removeClass('active');
                            $('#receipt-form').removeClass('show');
                        }

                        if (!$('#receipt-print-tab').hasClass('active')) {
                            $('#receipt-print-tab').addClass('active');
                            $('#receipt-print').addClass('active');
                            $('#receipt-print').addClass('show');
                        }
                        /* ========================================================================== */

                        $receiptFor.find('option[value="down_payment"]').attr('disabled', true);
                        $receiptFor.find('option[value="partial_payment"]').attr('disabled', true);
                        $receiptFor.find('option[value="final_payment"]').attr('disabled', true);
                    } else if (response.data.down_payment) {
                        $receiptFor.find('option[value="down_payment"]').attr('disabled', true);
                    } else {
                        $receiptFor.find('option[value="partial_payment"]').attr('disabled', true);
                        $receiptFor.find('option[value="final_payment"]').attr('disabled', true);
                    }

                    if (!$.empty(response.data.receipt_options)) {
                        $('select[name="receipt_options"]').html(response.data.receipt_options)
                    }

                    $totalReceiptEmount.val(response.data.total_receipt_amount)
                    $receiptInstallment.val(response.data.partial_payment)
                } else if (response.code != 'null') {
                    modalReceipt.modal('hide')

                    let errInfo = $.getErrorInfo(response.code)
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                } else {
                    modalReceipt.modal('hide');

                    let errInfo = $.getErrorInfo('SYS-BUG-E001')
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
            error: function(e) {
                modalReceipt.modal('hide');

                let errInfo = $.getErrorInfo('SYS-BUG-E001')
                if (!$.empty(errInfo)) {
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                }

                $.loader('hide')
            }
        })

        return output;
    }

    function formCheck() {
        let inputEmpty = false;

        $.each($('*[name*="receipt_"]'), function(key, value) {
            const e = $(value)
            const required = e.attr('invy-required')
            const inputValue = e.val()
            const name = e.attr('name')
            const lable = $('#lbl-' + name).text()
            const $error = $('#err-' + name)

            /* ======================= Max recept amount ======================= */
            if (name.toUpperCase() === 'RECEIPT_AMOUNT') {
                const receiptAmountMax = e.attr('max')
                const receiptAmountVal = e.val()

                const val = Number(receiptAmountVal);
                const max = Number(receiptAmountMax);

                if (val > max) {
                    e.val(max);
                }
            }
            /* ================================================================= */

            if (required && $.empty(inputValue)) {
                $error.text('"' + lable + '"' + ' is required, Cannot empty.')
                $error.show()

                inputEmpty = true;
            } else {
                $error.hide()
            }
        })

        if (!inputEmpty) {
            btnReleaseReceipt.attr('disabled', false)
        } else {
            btnReleaseReceipt.attr('disabled', true)
        }
    }

    function receiptDetailed(optionValue) {
        $.ajax({
            url: BASE_URL + 'payment_receipts/detailed',
            type: 'GET',
            dataType: 'json',
            async: true,
            beforeSend: function() {
                $.loader('show')
            },
            data: {
                whereclause: "receipt_number = '" + optionValue + "'"
            },
            success: function(response) {
                if (response.status) {
                    if ($.empty(response.data)) {
                        $viewReceiptOutstandingBalance.val('')
                        $viewReceiptAmount.val('')
                        $viewReceiptRemainingBalance.val('')
                        $viewReceiptDate.val('')

                        btnPrintReceipt.data('receiptnumber', '')
                        btnPrintReceipt.data('studentnumber', '')
                        btnPrintReceipt.attr('disabled', true)
                    } else {
                        $viewReceiptOutstandingBalance.val(response.data.outstanding_balance)
                        $viewReceiptAmount.val(response.data.amount)
                        $viewReceiptRemainingBalance.val(response.data.remaining_balance)
                        $viewReceiptDate.val(response.data.receipt_date)

                        btnPrintReceipt.attr('disabled', false)
                        btnPrintReceipt.data('receiptnumber', optionValue)
                        btnPrintReceipt.data('studentnumber', response.data.student_number)
                        generateReceipt(optionValue, response.data.student_number)
                    }
                } else if (response.code != 'null') {

                    $viewReceiptOutstandingBalance.val('')
                    $viewReceiptAmount.val('')
                    $viewReceiptRemainingBalance.val('')
                    $viewReceiptDate.val('')

                    btnPrintReceipt.data('receiptnumber', '')
                    btnPrintReceipt.data('studentnumber', '')
                    btnPrintReceipt.attr('disabled', true)

                    let errInfo = $.getErrorInfo(response.code)
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                } else {

                    $viewReceiptOutstandingBalance.val('')
                    $viewReceiptAmount.val('')
                    $viewReceiptRemainingBalance.val('')
                    $viewReceiptDate.val('')

                    btnPrintReceipt.data('receiptnumber', '')
                    btnPrintReceipt.data('studentnumber', '')
                    btnPrintReceipt.attr('disabled', true)

                    let errInfo = $.getErrorInfo('SYS-BUG-E001')
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
            error: function(e) {

                $viewReceiptOutstandingBalance.val('')
                $viewReceiptAmount.val('')
                $viewReceiptRemainingBalance.val('')
                $viewReceiptDate.val('')

                btnPrintReceipt.data('receiptnumber', '')
                btnPrintReceipt.data('studentnumber', '')
                btnPrintReceipt.attr('disabled', true)

                let errInfo = $.getErrorInfo('SYS-BUG-E001')
                if (!$.empty(errInfo)) {
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                }

                $.loader('hide')
            }
        })
    }

    function generateReceipt(receiptNumber, studentNumber) {
        const url = BASE_URL + `payment_receipts/generate_receipt/?receipt_number=` + bin2hex(receiptNumber) + `&student_number=` + bin2hex(studentNumber);
        window.open(url, '_blank', 'width=900,height=600');
    }

    $('select[name="receipt_for"], select[name="receipt_method"], input[name="receipt_method"], input[name="receipt_amount"]').on('input change', function() {
        if (receiptForSilentChange) {
            receiptForSilentChange = false;
            return;
        }

        formCheck()
    })

    $receiptOptions.on('change', function() {
        let optionValue = $(this).val();

        if (optionValue !== '') {
            receiptDetailed(optionValue)
        } else {
            $viewReceiptOutstandingBalance.val('')
            $viewReceiptAmount.val('')
            $viewReceiptRemainingBalance.val('')
            $viewReceiptDate.val('')

            btnPrintReceipt.data('receiptnumber', '')
            btnPrintReceipt.data('studentnumber', '')
            btnPrintReceipt.attr('disabled', true)
        }
    })

    btnPrintReceipt.on('click', function(e) {
        let studentNumber = $(this).data('studentnumber')
        let receiptNumber = $(this).data('receiptnumber')
        generateReceipt(receiptNumber, studentNumber)
    })

    $receiptFor.on('change', function() {
        if (receiptForSilentChange) {
            receiptForSilentChange = false;
            return;
        }

        let receiptFor = $(this).val()
        let dataPayment = payment(studentNumber, receiptFor)

        formCheck()
        if (receiptFor === 'partial_payment') {
            $receiptInstallment.parent().parent().css('display', '');
        } else {
            $receiptInstallment.parent().parent().css('display', 'none');
        }
    })

    btnReceipt.on('click', function() {
        modalReceipt.modal('show')
    })

    modalReceipt.on('show.bs.modal', function() {
        const d = new Date();

        const today =
            d.getFullYear() + '-' +
            String(d.getMonth() + 1).padStart(2, '0') + '-' +
            String(d.getDate()).padStart(2, '0');

        receiptForSilentChange = true;
        $receiptFor.val('').trigger('change');
        $receiptFor.find('option[value="down_payment"]').attr('disabled', false);
        $receiptFor.find('option[value="partial_payment"]').attr('disabled', false);
        $receiptFor.find('option[value="final_payment"]').attr('disabled', false);
        $receiptInstallment.val('')
        receiptForSilentChange = true;
        $receiptPaymentMethod.val('').trigger('change');
        $totalEmount.val('0')
        $receiptDate.val(today)
        $receiptAmount.val('0')
        $remainingBalance.val('0')
        $receiptNote.val('')
        $receiptNote.text('')
        btnReleaseReceipt.attr('disabled', true)

        onOpenModal(studentNumber);
    });

    modalReceipt.on('hide.bs.modal', function() {
        document.activeElement.blur();
    });

    btnReleaseReceipt.on('click', function() {
        let datas = {
            student_number: studentNumber,
            receipt_for: $receiptFor.val(),
            receipt_installment: $receiptInstallment.val(),
            receipt_method: $receiptPaymentMethod.val(),
            receipt_amount: $receiptAmount.val(),
            receipt_note: $receiptNote.val(),
            receipt_date: $receiptDate.val()
        }

        $.ajax({
            url: BASE_URL + 'payment_receipts/release_receipt',
            method: 'POST',
            async: true,
            dataType: 'json',
            data: datas,
            beforeSend: function() {
                $.loader('show')
            },
            success: function(response) {
                if (response.alert.status) {
                    modalReceipt.modal('hide')

                    generateReceipt(response.alert.data.receipt_number, response.alert.data.student_number)
                    $.invyAlert({
                        title: response.alert.code,
                        text: response.alert.message,
                        icon: response.alert.level,
                        redirectUrl: window.location.href
                    })

                } else if (response.alert.code != 'null') {
                    let errInfo = $.getErrorInfo(response.alert.code)
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                } else {
                    modalReceipt.modal('hide');

                    let errInfo = $.getErrorInfo('SYS-BUG-E001')
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
            error: function(e) {
                modalReceipt.modal('hide');

                let errInfo = $.getErrorInfo('SYS-BUG-E001')
                if (!$.empty(errInfo)) {
                    $.invyAlert({
                        title: errInfo.code,
                        text: errInfo.message,
                        icon: errInfo.level,
                        cabtn: errInfo.cabtn,
                        catext: errInfo.catext
                    })
                }

                $.loader('hide')
            }
        })
    })
})