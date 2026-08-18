(function($){'use strict';
var root=$('#counselor-report'), mode=root.data('view-mode'), nf=new Intl.NumberFormat('en-US');
var trend=echarts.init(document.getElementById('chart-counselor-trend'));
var sources=echarts.init(document.getElementById('chart-counselor-sources'));
$('.select2').select2({allowClear:true});
function esc(v){return $('<div>').text(v==null?'':v).html();}
function loading(on){$('#counselor-report-filter :input').prop('disabled',on);root.toggleClass('report-loading',on);on?[trend,sources].forEach(function(c){c.showLoading('default',{text:'Loading report...'});}):[trend,sources].forEach(function(c){c.hideLoading();});}
function rate(value,total){return total?Math.round((value/total)*1000)/10:0;}
function renderSummary(d){
    Object.keys(d.summary).forEach(function(k){$('[data-metric="'+k+'"]').text(k==='conversion_rate'?d.summary[k]:nf.format(d.summary[k]));});
    $('[data-period-label]').text(d.period.start&&d.period.end?d.period.start+' — '+d.period.end:'No matching period');
    if(mode==='personal'){
        var rates={conversion_rate:d.summary.conversion_rate,pending_rate:rate(d.summary.pending,d.summary.total),lost_rate:rate(d.summary.lost,d.summary.total),overdue_rate:rate(d.summary.overdue,d.summary.pending)};
        Object.keys(rates).forEach(function(k){$('[data-rate="'+k+'"]').text(rates[k]);$('[data-progress="'+k+'"]').css('width',Math.min(rates[k],100)+'%').attr('aria-valuenow',rates[k]);});
    }
}
function renderAdminTable(rows){
    var html=rows.map(function(r){var width=Math.min(r.conversion_rate,100);return '<tr><td><span class="font-weight-bold">'+esc(r.name)+'</span></td><td class="text-right">'+nf.format(r.total)+'</td><td class="text-right text-success">'+nf.format(r.converted)+'</td><td class="text-right text-warning">'+nf.format(r.pending)+'</td><td class="text-right text-danger">'+nf.format(r.lost)+'</td><td class="text-right '+(r.overdue?'text-danger font-weight-bold':'text-muted')+'">'+nf.format(r.overdue)+'</td><td><div class="d-flex align-items-center"><div class="progress progress-sm flex-fill mr-2"><div class="progress-bar bg-success" style="width:'+width+'%"></div></div><strong class="text-nowrap">'+r.conversion_rate+'%</strong></div></td></tr>';}).join('');
    $('#counselor-performance-body').html(html||'<tr><td colspan="7" class="text-center text-muted py-4">No data for selected filters.</td></tr>');
}
function renderCharts(d){
    trend.setOption({tooltip:{trigger:'axis'},legend:{top:0},grid:{top:50,left:40,right:20,bottom:35,containLabel:true},xAxis:{type:'category',data:d.trend.labels,axisTick:{show:false},axisLine:{lineStyle:{color:'#6c757d'}}},yAxis:{type:'value',minInterval:1,axisTick:{show:false},axisLine:{show:false},splitLine:{lineStyle:{color:'#e1e5eb'}}},series:[{name:'Total',type:'line',symbol:'circle',symbolSize:8,data:d.trend.total,itemStyle:{color:'#ffffff',borderColor:'#4e73df',borderWidth:2},lineStyle:{color:'#4e73df',width:3}},{name:'Converted',type:'bar',stack:'outcome',data:d.trend.converted,itemStyle:{color:'#28a745'}},{name:'Pending',type:'bar',stack:'outcome',data:d.trend.pending,itemStyle:{color:'#ffc107'}},{name:'Lost',type:'bar',stack:'outcome',data:d.trend.lost,itemStyle:{color:'#dc3545'}}],graphic:d.trend.labels.length?[]:[{type:'text',left:'center',top:'middle',style:{text:'No lead data',fill:'#6c757d'}}]},true);
    sources.setOption({tooltip:{trigger:'item',formatter:'{b}: {c} ({d}%)'},legend:{bottom:0},series:[{type:'pie',radius:['45%','70%'],center:['50%','44%'],data:d.sources,label:{formatter:'{b}\n{d}%'}}],graphic:d.sources.length?[]:[{type:'text',left:'center',top:'middle',style:{text:'No source data',fill:'#6c757d'}}]},true);
}
function load(){var filterData=$('#counselor-report-filter').serialize();$('#counselor-report-alert').addClass('d-none');loading(true);$.ajax({url:BASE_URL+'reports/counselor_overview',data:filterData,dataType:'json'}).done(function(r){if(!r.status){$('#counselor-report-alert').removeClass('d-none').text(r.message||'Unable to load report.');return;}renderSummary(r.data);renderCharts(r.data);if(mode==='admin')renderAdminTable(r.data.performance);}).fail(function(x){$('#counselor-report-alert').removeClass('d-none').text(x.responseJSON&&x.responseJSON.message?x.responseJSON.message:'Unable to load counselor report.');}).always(function(){loading(false);});}
$('#counselor-report-filter').on('submit',function(e){e.preventDefault();load();});$('#reset-counselor-report').on('click',function(){this.form.reset();$('.select2').val(null).trigger('change');load();});$(window).on('resize.counselorReport',function(){trend.resize();sources.resize();});load();
})(jQuery);
