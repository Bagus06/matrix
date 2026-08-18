<?php
$can_view_all = !empty($utilitys['can_view_all']);
$can_generate = !empty($utilitys['can_generate']);
$can_approve = !empty($utilitys['can_approve']);
$can_mark_paid = !empty($utilitys['can_mark_paid']);
$column_help = static function ($title, $content) {
    return '<button type="button" class="btn btn-link incentive-column-help"'
        . ' data-toggle="popover" data-trigger="focus" data-placement="auto"'
        . ' title="' . html_escape($title) . '" data-content="' . html_escape($content) . '"'
        . ' aria-label="About ' . html_escape($title) . '"><i class="fas fa-info-circle"></i></button>';
};
?>
<div id="incentive-report"
     data-can-generate="<?= $can_generate ? '1' : '0'; ?>"
     data-can-approve="<?= $can_approve ? '1' : '0'; ?>"
     data-can-mark-paid="<?= $can_mark_paid ? '1' : '0'; ?>">
    <div class="card card-outline card-primary mb-3">
        <div class="card-body py-3">
            <form id="incentive-filter" class="row align-items-end">
                <div class="col-sm-6 col-lg-3 mb-2">
                    <label class="small text-muted mb-1">Incentive Month</label>
                    <input type="month" class="form-control" name="period" value="<?= html_escape($utilitys['default_period']); ?>" required>
                </div>
                <?php if ($can_view_all) : ?>
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label class="small text-muted mb-1">Counselor</label>
                        <select class="form-control select2" name="assigned_to" style="width:100%">
                            <option value="">All counselors</option>
                            <?php foreach ($utilitys['counselors'] as $counselor) : ?>
                                <option value="<?= (int) $counselor['id']; ?>"><?= html_escape($counselor['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-sm-6 col-lg-2 mb-2">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter mr-1"></i> Apply</button>
                </div>
                <div class="col-sm-6 col-lg-3 mb-2 text-lg-right" id="incentive-actions">
                    <?php if ($can_generate) : ?><button type="button" class="btn btn-outline-primary d-none" id="generate-incentive"><i class="fas fa-calculator mr-1"></i> Generate Draft</button><?php endif; ?>
                    <?php if ($can_approve) : ?><button type="button" class="btn btn-success d-none" id="approve-incentive"><i class="fas fa-check mr-1"></i> Approve</button><?php endif; ?>
                    <?php if ($can_mark_paid) : ?><button type="button" class="btn btn-dark d-none" id="pay-incentive"><i class="fas fa-money-check-dollar mr-1"></i> Mark Paid</button><?php endif; ?>
                </div>
            </form>
            <?php if (!$can_view_all) : ?>
                <small class="text-muted"><i class="fas fa-lock mr-1"></i>Only your own approved or calculated incentive is displayed.</small>
            <?php endif; ?>
        </div>
    </div>

    <div id="incentive-alert" class="alert d-none"></div>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
        <div><span class="badge badge-secondary" id="incentive-status">Loading</span> <small class="text-muted ml-1" id="incentive-source"></small></div>
        <small class="text-muted" id="incentive-period-label"></small>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3"><div class="card card-primary card-outline h-100"><div class="card-body"><small class="text-muted">Qualified Admissions</small><h3 class="font-weight-bold mb-1" data-summary="admissions">—</h3><small><span data-summary="btech_admissions">—</span> B.Tech · <span data-summary="other_admissions">—</span> Other</small></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card card-info card-outline h-100"><div class="card-body"><small class="text-muted">New Business Value</small><h3 class="font-weight-bold mb-1" data-summary="total_bv">—</h3><small><span data-summary="eligible_bv">—</span> new eligible BV</small></div></div></div>
        <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0"><div class="card card-success card-outline h-100"><div class="card-body"><small class="text-muted">Initial Release</small><h3 class="font-weight-bold text-success mb-1" data-money="current_payable">—</h3><small>From newly qualified admissions</small></div></div></div>
        <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0"><div class="card card-warning card-outline h-100"><div class="card-body"><small class="text-muted">Total Payout</small><h3 class="font-weight-bold mb-1" data-money="total_payable">—</h3><small><span data-money="balance_released">—</span> deferred balance released</small></div></div></div>
    </div>

    <div class="card mt-3">
        <div class="card-header border-0"><h3 class="card-title font-weight-bold">Counselor Incentive Summary</h3></div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="incentive-summary-table">
                <thead>
                    <tr class="incentive-column-groups">
                        <th rowspan="2">Counselor <?= $column_help('Counselor', 'The counselor profile linked through the student\'s latest active lead assignment. Admin users are excluded from counselor incentive calculations.'); ?></th>
                        <th colspan="6" class="text-center incentive-performance-start">New Performance This Period</th>
                        <th colspan="2" class="text-center incentive-deferred-start">Deferred Payout</th>
                        <th colspan="2" class="text-center incentive-payout-start">Payout Schedule</th>
                    </tr>
                    <tr>
                        <th class="text-right incentive-performance-start">B.Tech <?= $column_help('New B.Tech Admissions', 'Students first qualified in the selected period whose normalized course code or name matches the configured B.Tech keyword. Each student is counted once after cumulative active receipts reach the configured B.Tech minimum payment.'); ?></th>
                        <th class="text-right">Other <?= $column_help('New Other Admissions', 'Students first qualified in the selected period whose course does not match the B.Tech rule. Each student is counted once after cumulative active receipts reach the configured Other minimum payment.'); ?></th>
                        <th class="text-right">New BV <?= $column_help('New Business Value', 'Configured B.Tech BV multiplied by new B.Tech admissions, plus configured Other BV multiplied by new Other admissions. BV from prior periods is never counted again.'); ?></th>
                        <th class="text-right">New Eligible BV <?= $column_help('New Eligible Business Value', 'MAX(0, New BV minus Qualifying BV). With a qualifying threshold of 10, the first 10 BV do not earn an incentive.'); ?></th>
                        <th class="text-right">Gross Earned <?= $column_help('Gross Incentive Earned', 'The progressive incentive earned from New Eligible BV. Each configured slab rate applies only to BV units inside that slab.'); ?></th>
                        <th class="text-right">Initial Release <?= $column_help('Initial Incentive Release', 'Gross Earned multiplied by the configured initial release percentage. This is the first incentive portion payable for admissions newly qualified in this period.'); ?></th>
                        <th class="text-right incentive-deferred-start">Settled Students <?= $column_help('Settled Students', 'The number of students whose cumulative active receipts reached the full payment amount in this period and whose retained incentive balance had not been released previously.'); ?></th>
                        <th class="text-right">Deferred Balance <?= $column_help('Deferred Balance Released', 'Retained incentive portions released after the related students became fully paid. Their original BV remains in the qualification period and is not added to New BV.'); ?></th>
                        <th class="text-right incentive-payout-start">Total Payout <?= $column_help('Total Incentive Payout', 'Initial Release plus Deferred Balance. This is the total incentive amount scheduled for payment from the selected report period.'); ?></th>
                        <th>Pay Date <?= $column_help('Scheduled Pay Date', 'The configured pay day in the month following the selected report period. For example, an August report with pay day 15 is scheduled for 15 September.'); ?></th>
                    </tr>
                </thead>
                <tbody id="incentive-summary-body"></tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-0"><h3 class="card-title font-weight-bold">Student Calculation Detail</h3><div class="card-tools"><small class="text-muted">Receipt-based qualification and settlement</small></div></div>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead><tr><th>Student</th><th>Counselor</th><th>Course</th><th>Category</th><th class="text-right">BV</th><th>Qualified</th><th>Fully Paid</th><th>Release</th><th class="text-right">Initial</th><th class="text-right">Balance</th></tr></thead>
                <tbody id="incentive-item-body"></tbody>
            </table>
        </div>
    </div>
</div>
