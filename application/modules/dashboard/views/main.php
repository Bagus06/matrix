<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-12" id="admin-dashboard">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div><h3 class="font-weight-bold mb-1">Admin Overview</h3><p class="text-muted mb-0">Current business position across leads, counselors, students, and payments.</p></div>
        <button type="button" class="btn btn-outline-primary mt-2 mt-sm-0" id="refresh-dashboard"><i class="fas fa-sync-alt mr-1"></i> Refresh</button>
    </div>
    <div id="dashboard-alert" class="alert alert-danger d-none"></div>
    <div class="row">
        <div class="col-sm-6 col-xl-3"><a href="<?=base_url('reports/leads')?>" class="text-reset"><div class="small-box bg-primary"><div class="inner"><h3 data-metric="leads">—</h3><p>Total leads · <span data-metric="conversion_rate">—</span>% converted</p></div><div class="icon"><i class="fas fa-users"></i></div></div></a></div>
        <div class="col-sm-6 col-xl-3"><a href="<?=base_url('reports/leads')?>" class="text-reset"><div class="small-box bg-warning"><div class="inner"><h3 data-metric="pending">—</h3><p>Pending · <span data-metric="followup_overdue">—</span> follow-ups late</p></div><div class="icon"><i class="fas fa-hourglass-half"></i></div></div></a></div>
        <div class="col-sm-6 col-xl-3"><a href="<?=base_url('students/main')?>" class="text-reset"><div class="small-box bg-info"><div class="inner"><h3 data-metric="students">—</h3><p>Active students</p></div><div class="icon"><i class="fas fa-user-graduate"></i></div></div></a></div>
        <div class="col-sm-6 col-xl-3"><a href="<?=base_url('reports/conselor')?>" class="text-reset"><div class="small-box bg-success"><div class="inner"><h3 data-metric="counselors">—</h3><p>Marketing counselors</p></div><div class="icon"><i class="fas fa-headset"></i></div></div></a></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="card card-success card-outline"><div class="card-body"><small class="text-muted">Collected</small><h3 class="font-weight-bold text-success" data-money="collected">—</h3><div class="progress progress-xs"><div class="progress-bar bg-success" id="collection-progress" style="width:0"></div></div><small><span id="collection-rate">—</span>% of <span data-money="billed">—</span> billed</small></div></div></div>
        <div class="col-md-4"><a href="<?=base_url('reports/payment_receipt')?>" class="text-reset"><div class="card card-danger card-outline"><div class="card-body"><small class="text-muted">Outstanding balance</small><h3 class="font-weight-bold text-danger" data-money="outstanding">—</h3><small><span data-metric="payment_overdue">—</span> students overdue</small></div></div></a></div>
        <div class="col-md-4"><a href="<?=base_url('reports/payment_receipt')?>" class="text-reset"><div class="card card-secondary card-outline"><div class="card-body"><small class="text-muted">Students without invoice</small><h3 class="font-weight-bold" data-metric="no_invoice">—</h3><small>Requires invoice preparation</small></div></div></a></div>
    </div>
    <div class="row">
        <div class="col-xl-8"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">12-Month Growth Overview</h3></div><div class="card-body"><div id="dashboard-growth-chart" style="height:390px"></div></div></div></div>
        <div class="col-xl-4 mt-3 mt-xl-0"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Billing vs Collection</h3></div><div class="card-body"><div id="dashboard-payment-chart" style="height:390px"></div></div></div></div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-6"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Counselor Snapshot</h3><div class="card-tools"><a href="<?=base_url('reports/conselor')?>">Full report</a></div></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Counselor</th><th class="text-right">Leads</th><th class="text-right">Converted</th><th class="text-right">Late</th><th>Rate</th></tr></thead><tbody id="dashboard-counselors"></tbody></table></div></div></div>
        <div class="col-xl-6 mt-3 mt-xl-0"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Payment Attention</h3><div class="card-tools"><a href="<?=base_url('reports/payment_receipt')?>">Full report</a></div></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Student</th><th>Due date</th><th class="text-right">Balance</th><th class="text-right">Late</th></tr></thead><tbody id="dashboard-attention"></tbody></table></div></div></div>
    </div>
</div>
