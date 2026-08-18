<?php $filters = $report_filters ?? ['sources' => [], 'universities' => [], 'counselors' => []]; ?>
<div class="col-12" id="leads-report">
    <div class="card card-outline card-primary mb-3">
        <div class="card-body py-3">
            <form id="leads-report-filter" class="row align-items-end">
                <div class="col-sm-6 col-lg-2 mb-2">
                    <label class="small text-muted mb-1">Start date</label>
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-sm-6 col-lg-2 mb-2">
                    <label class="small text-muted mb-1">End date</label>
                    <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-sm-6 col-lg-2 mb-2">
                    <label class="small text-muted mb-1">Lead source</label>
                    <select class="form-control select2" name="source_code" style="width:100%">
                        <option value="">All sources</option>
                        <?php foreach ($filters['sources'] as $source) : ?>
                            <option value="<?= html_escape($source['source_code']); ?>"><?= html_escape($source['source_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2 mb-2">
                    <label class="small text-muted mb-1">University</label>
                    <select class="form-control select2" name="university_id" style="width:100%">
                        <option value="">All universities</option>
                        <?php foreach ($filters['universities'] as $university) : ?>
                            <option value="<?= (int) $university['id']; ?>"><?= html_escape($university['university_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2 mb-2">
                    <label class="small text-muted mb-1">Counselor</label>
                    <select class="form-control select2" name="assigned_to" style="width:100%">
                        <option value="">All counselors</option>
                        <?php foreach ($filters['counselors'] as $counselor) : ?>
                                <option value="<?= (int) $counselor['id']; ?>"><?= html_escape($counselor['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2 mb-2 d-flex">
                    <button type="submit" class="btn btn-primary flex-fill mr-2"><i class="fas fa-filter mr-1"></i> Apply</button>
                    <button type="button" id="reset-leads-report" class="btn btn-default" title="Reset filters"><i class="fas fa-undo"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div id="leads-report-alert" class="alert alert-danger d-none"></div>
    <div class="row" id="leads-overview-cards">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-primary card-outline h-100"><div class="card-body">
                <div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Enquiry Total</p><h3 class="font-weight-bold mb-0" data-metric="total">—</h3><small class="text-muted" data-period-label>All active leads</small></div><span class="text-primary"><i class="fa-solid fa-2x fa-users"></i></span></div>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-success card-outline h-100"><div class="card-body">
                <div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Converted</p><h3 class="font-weight-bold mb-0" data-metric="converted">—</h3><small class="text-success"><span data-metric="conversion_rate">—</span>% conversion rate</small></div><span class="text-success"><i class="fa-solid fa-2x fa-circle-check"></i></span></div>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0">
            <div class="card card-warning card-outline h-100"><div class="card-body">
                <div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Pending</p><h3 class="font-weight-bold mb-0" data-metric="pending">—</h3><small class="text-warning"><span data-metric="overdue">—</span> overdue follow-ups</small></div><span class="text-warning"><i class="fa-solid fa-2x fa-hourglass-half"></i></span></div>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0">
            <div class="card card-danger card-outline h-100"><div class="card-body">
                <div class="d-flex justify-content-between"><div><p class="text-muted mb-1">Lost</p><h3 class="font-weight-bold mb-0" data-metric="lost">—</h3><small class="text-muted">Status NO</small></div><span class="text-danger"><i class="fa-solid fa-2x fa-circle-xmark"></i></span></div>
            </div></div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-8">
            <div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Enquiry Trend</h3><div class="card-tools"><span class="badge badge-light" id="trend-grouping"></span></div></div><div class="card-body"><div id="chart-enquiry-trend" style="height:420px"></div></div></div>
        </div>
        <div class="col-xl-4 mt-3 mt-xl-0">
            <div class="card h-100"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Lead Status</h3></div><div class="card-body"><div id="chart-lead-status" style="height:420px"></div></div></div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-6">
            <div class="card"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Top Lead Sources</h3></div><div class="card-body table-responsive p-0"><table class="table table-hover mb-0"><thead><tr><th>Source</th><th class="text-right">Leads</th><th class="text-right">Converted</th><th class="text-right">Rate</th></tr></thead><tbody id="top-sources-body"></tbody></table></div></div>
        </div>
        <div class="col-xl-6">
            <div class="card"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Counselor Performance</h3></div><div class="card-body table-responsive p-0"><table class="table table-hover mb-0"><thead><tr><th>Counselor</th><th class="text-right">Leads</th><th class="text-right">Converted</th><th class="text-right">Overdue</th><th class="text-right">Rate</th></tr></thead><tbody id="top-counselors-body"></tbody></table></div></div>
        </div>
    </div>
</div>
