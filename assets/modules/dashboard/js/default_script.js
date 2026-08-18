(function ($) {
    'use strict';
    var growth = echarts.init(document.getElementById('dashboard-growth-chart'));
    var payment = echarts.init(document.getElementById('dashboard-payment-chart'));
    var num = new Intl.NumberFormat('en-IN');
    var money = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 });
    var lineStyle = { symbol: 'circle', symbolSize: 8, itemStyle: { color: '#ffffff', borderColor: '#4e73df', borderWidth: 2 }, lineStyle: { color: '#4e73df', width: 3 } };
    var axes = { axisTick: { show: false }, axisLine: { lineStyle: { color: '#6c757d' } } };
    var valueAxis = { type: 'value', axisTick: { show: false }, axisLine: { show: false }, splitLine: { lineStyle: { color: '#e1e5eb' } } };

    function esc(v) { return $('<div>').text(v == null ? '' : v).html(); }
    function loading(on) {
        [growth, payment].forEach(function (chart) { on ? chart.showLoading('default', { text: 'Loading overview...' }) : chart.hideLoading(); });
        $('#refresh-dashboard').prop('disabled', on).find('i').toggleClass('fa-spin', on);
    }
    function render(d) {
        Object.keys(d.summary).forEach(function (key) { $('[data-metric="' + key + '"]').text(key === 'conversion_rate' ? d.summary[key] : num.format(d.summary[key])); });
        Object.keys(d.summary).forEach(function (key) { $('[data-money="' + key + '"]').text(money.format(d.summary[key])); });
        var rate = d.summary.billed ? Math.round(d.summary.collected / d.summary.billed * 1000) / 10 : 0;
        $('#collection-rate').text(rate);
        $('#collection-progress').css('width', Math.min(rate, 100) + '%');

        growth.setOption({
            tooltip: { trigger: 'axis' }, legend: { top: 0 }, grid: { top: 45, left: 40, right: 20, bottom: 35, containLabel: true },
            xAxis: $.extend({ type: 'category', data: d.monthly.labels }, axes),
            yAxis: $.extend({ minInterval: 1 }, valueAxis),
            series: [$.extend({ name: 'Leads', type: 'line', data: d.monthly.leads }, lineStyle), { name: 'Converted', type: 'bar', data: d.monthly.converted, itemStyle: { color: '#28a745' } }, { name: 'Students', type: 'bar', data: d.monthly.students, itemStyle: { color: '#17a2b8' } }]
        }, true);
        payment.setOption({
            tooltip: { trigger: 'axis', valueFormatter: function (v) { return money.format(v); } }, legend: { top: 0 }, grid: { top: 45, left: 40, right: 20, bottom: 35, containLabel: true },
            xAxis: $.extend({ type: 'category', data: d.monthly.labels, axisLabel: { rotate: 35 } }, axes),
            yAxis: $.extend({ axisLabel: { formatter: function (v) { return money.format(v); } } }, valueAxis),
            series: [{ name: 'Billed', type: 'bar', data: d.monthly.billed, itemStyle: { color: '#0B2D84' } }, $.extend({ name: 'Collected', type: 'line', data: d.monthly.collected }, lineStyle)]
        }, true);
        var counselors = d.counselors.map(function (r) { return '<tr><td><b>' + esc(r.name) + '</b></td><td class="text-right">' + num.format(r.total) + '</td><td class="text-right text-success">' + num.format(r.converted) + '</td><td class="text-right ' + (r.overdue ? 'text-danger font-weight-bold' : 'text-muted') + '">' + num.format(r.overdue) + '</td><td style="min-width:110px"><div class="d-flex align-items-center"><div class="progress progress-xs flex-fill mr-2"><div class="progress-bar bg-success" style="width:' + Math.min(r.rate, 100) + '%"></div></div><small>' + r.rate + '%</small></div></td></tr>'; }).join('');
        $('#dashboard-counselors').html(counselors || '<tr><td colspan="5" class="text-center text-muted">No data</td></tr>');
        var attention = d.attention.map(function (r) { return '<tr><td><a href="' + BASE_URL + 'students/edit/' + r.edit_key + '"><b>' + esc(r.full_name) + '</b></a><br><small>' + esc(r.student_number) + '</small></td><td>' + esc(r.due_date) + '</td><td class="text-right text-danger">' + money.format(r.remaining_balance) + '</td><td class="text-right"><span class="badge badge-danger">' + num.format(r.overdue_days) + ' days</span></td></tr>'; }).join('');
        $('#dashboard-attention').html(attention || '<tr><td colspan="4" class="text-center text-muted">No overdue payments</td></tr>');
    }
    function fetch() { loading(true); $('#dashboard-alert').addClass('d-none'); $.getJSON(BASE_URL + 'dashboard/overview').done(function (r) { if (r.status) render(r.data); else $('#dashboard-alert').removeClass('d-none').text(r.message || 'Unable to load dashboard.'); }).fail(function () { $('#dashboard-alert').removeClass('d-none').text('Unable to load admin overview.'); }).always(function () { loading(false); }); }
    $('#refresh-dashboard').on('click', fetch);
    $(window).on('resize.adminDashboard', function () { growth.resize(); payment.resize(); });
    fetch();
})(jQuery);
