(function ($) {
    'use strict';

    var trendElement = document.getElementById('chart-enquiry-trend');
    var statusElement = document.getElementById('chart-lead-status');
    if (!trendElement || !statusElement || typeof echarts === 'undefined') return;

    var trendChart = echarts.init(trendElement);
    var statusChart = echarts.init(statusElement);
    var numberFormat = new Intl.NumberFormat('en-US');
    var colors = ['#0B2D84', '#2f80ed', '#56ccf2', '#6fcf97', '#f2c94c', '#f2994a', '#9b51e0', '#828282'];

    $('.select2').select2({ theme: 'default', allowClear: true });

    function setLoading(loading) {
        trendChart.showLoading(loading ? 'default' : undefined, { text: 'Loading report...' });
        statusChart.showLoading(loading ? 'default' : undefined, { text: 'Loading report...' });
        if (!loading) {
            trendChart.hideLoading();
            statusChart.hideLoading();
        }
        $('#leads-report-filter :input').prop('disabled', loading);
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function emptyRow(colspan) {
        return '<tr><td colspan="' + colspan + '" class="text-center text-muted py-4">No data for the selected filters.</td></tr>';
    }

    function renderSummary(data) {
        Object.keys(data.summary).forEach(function (key) {
            $('[data-metric="' + key + '"]').text(key === 'conversion_rate' ? data.summary[key] : numberFormat.format(data.summary[key]));
        });
        var label = data.period.start && data.period.end ? data.period.start + ' — ' + data.period.end : 'No matching period';
        $('[data-period-label]').text(label);
        $('#trend-grouping').text(data.period.grouping + ' view');
    }

    function renderTrend(trend) {
        var series = trend.sources.map(function (source, index) {
            return {
                name: source.name,
                type: 'bar',
                stack: 'Leads by source',
                barMaxWidth: 32,
                emphasis: { focus: 'series' },
                itemStyle: { color: colors[index % colors.length] },
                data: source.data
            };
        });
        series.push({
            name: 'Total Leads', type: 'line', symbol: 'circle', symbolSize: 8,
            itemStyle: { color: '#ffffff', borderColor: '#4e73df', borderWidth: 2 }, lineStyle: { color: '#4e73df', width: 3 }, data: trend.total
        });
        trendChart.setOption({
            color: colors,
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
            legend: { type: 'scroll', top: 0 },
            grid: { top: 55, left: 45, right: 25, bottom: 55, containLabel: true },
            dataZoom: trend.labels.length > 14 ? [{ type: 'inside' }, { type: 'slider', height: 18, bottom: 8 }] : [],
            xAxis: { type: 'category', data: trend.labels, axisTick: { show: false }, axisLine: { lineStyle: { color: '#6c757d' } }, axisLabel: { rotate: trend.labels.length > 10 ? 35 : 0 } },
            yAxis: { type: 'value', minInterval: 1, name: 'Leads', axisTick: { show: false }, axisLine: { show: false }, splitLine: { lineStyle: { color: '#e1e5eb' } } },
            series: series,
            graphic: trend.labels.length ? [] : [{ type: 'text', left: 'center', top: 'middle', style: { text: 'No lead data', fill: '#6c757d', fontSize: 14 } }]
        }, true);
    }

    function renderStatus(rows) {
        var statusColors = { YES: '#28a745', PENDING: '#ffc107', NO: '#dc3545', UNKNOWN: '#6c757d' };
        statusChart.setOption({
            tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
            legend: { bottom: 0, left: 'center' },
            series: [{
                name: 'Lead Status', type: 'pie', radius: ['48%', '72%'], center: ['50%', '45%'],
                avoidLabelOverlap: true,
                label: { formatter: '{b}\n{c} ({d}%)' },
                data: rows.map(function (row) { return { name: row.name, value: row.value, itemStyle: { color: statusColors[row.name] || '#17a2b8' } }; })
            }],
            graphic: rows.length ? [] : [{ type: 'text', left: 'center', top: 'middle', style: { text: 'No status data', fill: '#6c757d', fontSize: 14 } }]
        }, true);
    }

    function renderTables(data) {
        var sources = data.top_sources.map(function (row) {
            return '<tr><td>' + escapeHtml(row.name) + '</td><td class="text-right">' + numberFormat.format(row.total) + '</td><td class="text-right">' + numberFormat.format(row.converted) + '</td><td class="text-right"><span class="badge badge-light">' + row.conversion_rate + '%</span></td></tr>';
        }).join('');
        $('#top-sources-body').html(sources || emptyRow(4));

        var counselors = data.top_counselors.map(function (row) {
            var overdueClass = row.overdue > 0 ? 'text-danger font-weight-bold' : 'text-muted';
            return '<tr><td>' + escapeHtml(row.name) + '</td><td class="text-right">' + numberFormat.format(row.total) + '</td><td class="text-right">' + numberFormat.format(row.converted) + '</td><td class="text-right ' + overdueClass + '">' + numberFormat.format(row.overdue) + '</td><td class="text-right"><span class="badge badge-light">' + row.conversion_rate + '%</span></td></tr>';
        }).join('');
        $('#top-counselors-body').html(counselors || emptyRow(5));
    }

    function loadReport() {
        $('#leads-report-alert').addClass('d-none').text('');
        var filterData = $('#leads-report-filter').serialize();
        setLoading(true);
        $.ajax({ url: BASE_URL + 'reports/leads_overview', method: 'GET', dataType: 'json', data: filterData })
            .done(function (response) {
                if (!response.status) {
                    $('#leads-report-alert').removeClass('d-none').text(response.message || 'Unable to load report.');
                    return;
                }
                renderSummary(response.data);
                renderTrend(response.data.trend);
                renderStatus(response.data.status);
                renderTables(response.data);
            })
            .fail(function (xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to load the leads report.';
                $('#leads-report-alert').removeClass('d-none').text(message);
            })
            .always(function () { setLoading(false); });
    }

    $('#leads-report-filter').on('submit', function (event) { event.preventDefault(); loadReport(); });
    $('#reset-leads-report').on('click', function () {
        document.getElementById('leads-report-filter').reset();
        $('.select2').val(null).trigger('change');
        loadReport();
    });
    $(window).on('resize.leadsReport', function () { trendChart.resize(); statusChart.resize(); });
    loadReport();
})(jQuery);
