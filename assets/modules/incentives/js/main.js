(function ($) {
    'use strict';

    var root = $('#incentive-report');
    if (!root.length) return;
    var canGenerate = root.data('can-generate') === 1;
    var canApprove = root.data('can-approve') === 1;
    var canMarkPaid = root.data('can-mark-paid') === 1;
    var currentRun = null;
    var money = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 2 });
    var number = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2 });

    $('.select2').select2({ theme: 'default', allowClear: true });
    root.find('.incentive-column-help').popover({ container: 'body', trigger: 'focus' });

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function showAlert(message, type) {
        $('#incentive-alert').removeClass('d-none alert-danger alert-success alert-info').addClass('alert-' + (type || 'danger')).text(message);
    }

    function setLoading(loading) {
        $('#incentive-filter :input, #incentive-actions button').prop('disabled', loading);
        if (loading) $('#incentive-status').removeClass().addClass('badge badge-secondary').text('Loading…');
    }

    function statusClass(status) {
        return { DRAFT: 'warning', APPROVED: 'success', PAID: 'dark' }[status] || 'info';
    }

    function renderActions(data) {
        currentRun = data.run;
        $('#generate-incentive').toggleClass('d-none', !canGenerate || (currentRun && currentRun.status !== 'DRAFT'));
        $('#approve-incentive').toggleClass('d-none', !canApprove || !currentRun || currentRun.status !== 'DRAFT');
        $('#pay-incentive').toggleClass('d-none', !canMarkPaid || !currentRun || currentRun.status !== 'APPROVED');
        var status = currentRun ? currentRun.status : 'LIVE PREVIEW';
        $('#incentive-status').removeClass().addClass('badge badge-' + statusClass(currentRun ? currentRun.status : '')).text(status);
        $('#incentive-source').text(data.source === 'SNAPSHOT' ? 'Saved monthly snapshot' : 'Not saved — values follow current receipts and setup');
    }

    function renderSummary(data) {
        var summary = data.summary;
        summary.admissions = Number(summary.btech_admissions) + Number(summary.other_admissions);
        Object.keys(summary).forEach(function (key) {
            $('[data-summary="' + key + '"]').text(number.format(summary[key]));
            $('[data-money="' + key + '"]').text(money.format(summary[key]));
        });
        $('#incentive-period-label').text(data.period.start + ' — ' + data.period.end);
    }

    function renderRows(rows) {
        var html = rows.map(function (row) {
            return '<tr><td class="font-weight-bold">' + escapeHtml(row.counselor) + '</td>' +
                '<td class="text-right incentive-performance-start">' + number.format(row.btech_admissions) + '</td>' +
                '<td class="text-right">' + number.format(row.other_admissions) + '</td>' +
                '<td class="text-right">' + number.format(row.total_bv) + '</td>' +
                '<td class="text-right">' + number.format(row.eligible_bv) + '</td>' +
                '<td class="text-right">' + money.format(row.gross_incentive) + '</td>' +
                '<td class="text-right text-success">' + money.format(row.current_payable) + '</td>' +
                '<td class="text-right incentive-deferred-start">' + number.format(Number(row.settled_students || 0)) + '</td>' +
                '<td class="text-right text-primary">' + money.format(row.balance_released) + '</td>' +
                '<td class="text-right font-weight-bold incentive-payout-start">' + money.format(row.total_payable) + '</td>' +
                '<td>' + escapeHtml(row.pay_date || '-') + '</td></tr>';
        }).join('');
        $('#incentive-summary-body').html(html || '<tr><td colspan="11" class="text-center text-muted py-4">No eligible counselor data.</td></tr>');
    }

    function renderItems(items) {
        var html = items.map(function (item) {
            var releases = [];
            if (Number(item.initial_payable) > 0) releases.push('<span class="badge badge-success">Initial</span>');
            if (Number(item.balance_released) > 0) releases.push('<span class="badge badge-primary">Balance</span>');
            var studentUrl = BASE_URL + 'students/edit/' + (item.student_edit_id || '');
            var student = item.student_edit_id ? '<a class="font-weight-bold" href="' + studentUrl + '">' + escapeHtml(item.student_number) + '</a>' : '<span class="font-weight-bold">' + escapeHtml(item.student_number) + '</span>';
            return '<tr><td>' + student + '</td><td>' + escapeHtml(item.counselor) + '</td>' +
                '<td><span class="font-weight-bold">' + escapeHtml(item.course_code || '-') + '</span><br><small class="text-muted">' + escapeHtml(item.course_name) + '</small></td>' +
                '<td><span class="badge badge-light border">' + escapeHtml(item.category_code) + '</span></td>' +
                '<td class="text-right">' + number.format(item.bv) + '</td><td>' + escapeHtml(item.initial_qualified_date) + '</td>' +
                '<td>' + escapeHtml(item.full_payment_date || '-') + '</td><td>' + releases.join(' ') + '</td>' +
                '<td class="text-right">' + money.format(item.initial_payable) + '</td><td class="text-right">' + money.format(item.balance_released) + '</td></tr>';
        }).join('');
        $('#incentive-item-body').html(html || '<tr><td colspan="10" class="text-center text-muted py-4">No incentive release for students in this month.</td></tr>');
    }

    function loadReport() {
        var filterData = {
            period: $('#incentive-filter [name="period"]').val()
        };
        var counselor = $('#incentive-filter [name="assigned_to"]');
        if (counselor.length) filterData.assigned_to = counselor.val();

        $('#incentive-alert').addClass('d-none');
        setLoading(true);
        $.ajax({ url: BASE_URL + 'incentives/overview', data: filterData, dataType: 'json' })
            .done(function (response) {
                if (!response.status) return showAlert(response.message || 'Unable to load incentive data.');
                renderActions(response.data);
                renderSummary(response.data);
                renderRows(response.data.rows);
                renderItems(response.data.items);
            })
            .fail(function (xhr) { showAlert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to load incentive data.'); })
            .always(function () { setLoading(false); });
    }

    function runAction(url, data, confirmation) {
        Swal.fire({ title: confirmation, icon: 'question', showCancelButton: true, confirmButtonText: 'Continue' }).then(function (result) {
            if (!result.isConfirmed) return;
            setLoading(true);
            $.ajax({ url: BASE_URL + url, method: 'POST', dataType: 'json', data: data })
                .done(function (response) { showAlert(response.message, response.status ? 'success' : 'danger'); if (response.status) loadReport(); })
                .fail(function (xhr) { showAlert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to process incentive action.'); })
                .always(function () { setLoading(false); });
        });
    }

    $('#incentive-filter').on('submit', function (event) { event.preventDefault(); loadReport(); });
    $('#generate-incentive').on('click', function () { runAction('incentives/generate', { period: $('#incentive-filter [name="period"]').val() }, 'Generate or refresh this monthly draft?'); });
    $('#approve-incentive').on('click', function () { if (currentRun) runAction('incentives/approve', { run_id: currentRun.id }, 'Approve and freeze this incentive run?'); });
    $('#pay-incentive').on('click', function () { if (currentRun) runAction('incentives/mark_paid', { run_id: currentRun.id }, 'Mark this incentive run as paid?'); });
    loadReport();
})(jQuery);
