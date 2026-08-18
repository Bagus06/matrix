<?php
$filters = $report_filters ?? ['sources'=>[],'universities'=>[],'counselors'=>[]];
$is_admin_report = !empty($can_view_all);
?>
<div class="col-12" id="counselor-report" data-view-mode="<?= $is_admin_report ? 'admin' : 'personal'; ?>">
    <div class="card card-outline card-primary"><div class="card-body py-3">
        <form id="counselor-report-filter" class="row align-items-end">
            <div class="col-sm-6 col-lg-2 mb-2"><label class="small text-muted mb-1">Start date</label><input type="date" class="form-control" name="start_date"></div>
            <div class="col-sm-6 col-lg-2 mb-2"><label class="small text-muted mb-1">End date</label><input type="date" class="form-control" name="end_date"></div>
            <div class="col-sm-6 col-lg-2 mb-2"><label class="small text-muted mb-1">Lead source</label><select class="form-control select2" name="source_code" style="width:100%"><option value="">All sources</option><?php foreach($filters['sources'] as $v): ?><option value="<?= html_escape($v['source_code']); ?>"><?= html_escape($v['source_name']); ?></option><?php endforeach; ?></select></div>
            <div class="col-sm-6 col-lg-2 mb-2"><label class="small text-muted mb-1">University</label><select class="form-control select2" name="university_id" style="width:100%"><option value="">All universities</option><?php foreach($filters['universities'] as $v): ?><option value="<?= (int)$v['id']; ?>"><?= html_escape($v['university_name']); ?></option><?php endforeach; ?></select></div>
            <?php if ($is_admin_report): ?><div class="col-sm-6 col-lg-2 mb-2"><label class="small text-muted mb-1">Counselor</label><select class="form-control select2" name="assigned_to" style="width:100%"><option value="">All counselors</option><?php foreach($filters['counselors'] as $v): ?><option value="<?= (int)$v['id']; ?>"><?= html_escape($v['name']); ?></option><?php endforeach; ?></select></div><?php endif; ?>
            <div class="col-sm-6 <?= $is_admin_report?'col-lg-2':'col-lg-4'; ?> mb-2 d-flex"><button class="btn btn-primary flex-fill mr-2" type="submit"><i class="fas fa-filter mr-1"></i> Apply</button><button class="btn btn-default" type="button" id="reset-counselor-report"><i class="fas fa-undo"></i></button></div>
        </form>
        <?php if (!$is_admin_report): ?><small class="text-muted"><i class="fas fa-lock mr-1"></i>Only leads assigned to your account are included.</small><?php endif; ?>
    </div></div>
    <div id="counselor-report-alert" class="alert alert-danger d-none"></div>

    <div class="row">
        <div class="col-xl-8"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Monthly Counselor Outcomes</h3></div><div class="card-body"><div id="chart-counselor-trend" style="height:410px"></div></div></div></div>
        <div class="col-xl-4 mt-3 mt-xl-0"><div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Lead Source Mix</h3></div><div class="card-body"><div id="chart-counselor-sources" style="height:410px"></div></div></div></div>
    </div>

    <?php if ($is_admin_report): ?>
        <div class="card mt-3">
            <div class="card-header border-0"><h3 class="card-title font-weight-bold">Counselor Performance</h3><div class="card-tools"><span class="text-muted small" data-period-label>All periods</span></div></div>
            <div class="table-responsive"><table class="table table-hover mb-0">
                <thead><tr><th>Counselor</th><th class="text-right">Assigned</th><th class="text-right">Converted</th><th class="text-right">Pending</th><th class="text-right">Lost</th><th class="text-right">Overdue</th><th style="min-width:180px">Conversion Rate</th></tr></thead>
                <tbody id="counselor-performance-body"></tbody>
                <tfoot><tr class="font-weight-bold"><td>Total</td><td class="text-right" data-metric="total">—</td><td class="text-right" data-metric="converted">—</td><td class="text-right" data-metric="pending">—</td><td class="text-right" data-metric="lost">—</td><td class="text-right" data-metric="overdue">—</td><td><span data-metric="conversion_rate">—</span>% overall</td></tr></tfoot>
            </table></div>
        </div>
    <?php else: ?>
        <div class="card bg-gradient-primary mt-3"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><p class="mb-1 text-white-50">My Assigned Leads</p><h2 class="font-weight-bold mb-0" data-metric="total">—</h2><small data-period-label>Selected period</small></div><i class="fas fa-user-check fa-3x text-white-50"></i></div></div></div>
        <div class="row" id="personal-rate-cards">
            <?php foreach ([['success','conversion_rate','Conversion Rate','converted','converted leads','fa-circle-check'],['warning','pending_rate','Pending Rate','pending','pending leads','fa-hourglass-half'],['danger','lost_rate','Lost Rate','lost','lost leads','fa-circle-xmark'],['info','overdue_rate','Overdue Rate','overdue','of pending leads overdue','fa-calendar-times']] as $card): ?>
            <div class="col-sm-6 col-xl-3"><div class="card card-<?= $card[0]; ?> card-outline h-100"><div class="card-body"><div class="d-flex justify-content-between"><div><p class="text-muted mb-1"><?= $card[2]; ?></p><h2 class="font-weight-bold mb-0"><span data-rate="<?= $card[1]; ?>">—</span><small class="text-lg">%</small></h2><small class="text-<?= $card[0]; ?>"><span data-metric="<?= $card[3]; ?>">—</span> <?= $card[4]; ?></small></div><span class="text-<?= $card[0]; ?>"><i class="fas fa-2x <?= $card[5]; ?>"></i></span></div><div class="progress progress-xs mt-3"><div class="progress-bar bg-<?= $card[0]; ?>" data-progress="<?= $card[1]; ?>" style="width:0"></div></div></div></div></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
