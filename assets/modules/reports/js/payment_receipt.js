(function ($) {
    var root = $('#payment-report');
    var trend = echarts.init(document.getElementById('payment-trend'));
    var status = echarts.init(document.getElementById('payment-status'));
    var money = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 });
    var num = new Intl.NumberFormat('en-IN');
    var lineStyle = { symbol: 'circle', symbolSize: 8, itemStyle: { color: '#ffffff', borderColor: '#4e73df', borderWidth: 2 }, lineStyle: { color: '#4e73df', width: 3 } };
    $('.select2').select2({ allowClear: true });
    function load(on) { root.find(':input').prop('disabled', on); [trend, status].forEach(function (chart) { on ? chart.showLoading('default', { text: 'Loading...' }) : chart.hideLoading(); }); }
    function filters() { var data = {}; $('#payment-report-filter [name]').each(function () { data[this.name] = $(this).val() || ''; }); return data; }
    function render(d) {
        Object.keys(d.summary).forEach(function (key) { $('[data-metric="' + key + '"]').text(num.format(d.summary[key])); });
        Object.keys(d.summary).forEach(function (key) { $('[data-money="' + key + '"]').text(money.format(d.summary[key])); });
        trend.setOption({
            tooltip: { trigger: 'axis', valueFormatter: function (value) { return money.format(value); } }, legend: { top: 0 }, grid: { top: 45, left: 55, right: 20, bottom: 35 },
            xAxis: { type: 'category', data: d.trend.labels, axisTick: { show: false }, axisLine: { lineStyle: { color: '#6c757d' } } },
            yAxis: { type: 'value', axisTick: { show: false }, axisLine: { show: false }, splitLine: { lineStyle: { color: '#e1e5eb' } }, axisLabel: { formatter: function (value) { return money.format(value); } } },
            series: [{ name: 'Billed', type: 'bar', data: d.trend.billed, itemStyle: { color: '#0B2D84' } }, $.extend({ name: 'Collected', type: 'line', data: d.trend.collected }, lineStyle)]
        }, true);
        status.setOption({ tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' }, legend: { bottom: 0 }, series: [{ type: 'pie', radius: ['45%', '70%'], center: ['50%', '44%'], data: d.status, label: { formatter: '{b}\n{c}' } }] }, true);
    }
    function reloadPriority() { if ($.fn.DataTable.isDataTable('#tb-payment-priority')) $('#tb-payment-priority').DataTable().ajax.reload(); }
    function fetch() { var data = $('#payment-report-filter').serialize(); load(true); $.ajax({ url: BASE_URL + 'reports/payment_receipt_overview', data: data, dataType: 'json' }).done(function (response) { if (response.status) { render(response.data); reloadPriority(); } else $('#payment-report-alert').removeClass('d-none').text(response.message || 'Unable to load report.'); }).fail(function () { $('#payment-report-alert').removeClass('d-none').text('Unable to load payment report.'); }).always(function () { load(false); }); }
    $('#tb-payment-priority').ssDtTable({ url: BASE_URL + 'reports/payment_priority_table', table_main: true, identity: 'PAYMENT_PRIORITY', ajaxData: filters, style: { orderableCol: [0, 7, 8], colNowrap: [1, 2, 3, 4, 5, 6, 7], colAlRight: [3, 4] } });
    $('#payment-report-filter').on('submit', function (event) { event.preventDefault(); fetch(); });
    $('#reset-payment-report').on('click', function () { this.form.reset(); $('.select2').val(null).trigger('change'); fetch(); });
    $(window).on('resize.payment', function () { trend.resize(); status.resize(); });
    fetch();
})(jQuery);
