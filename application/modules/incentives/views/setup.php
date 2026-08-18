<?php
$plan = $utilitys['plan'] ?? [];
$rules = [];
foreach (($utilitys['rules'] ?? []) as $rule) $rules[$rule['category_code']] = $rule;
$btech = $rules['BTECH'] ?? [];
$other = $rules['OTHER'] ?? [];
$slabs = $utilitys['slabs'] ?? [];
?>
<div class="card card-outline card-primary" id="incentive-setup">
    <div class="card-header border-0">
        <h3 class="card-title font-weight-bold">Incentive Setup</h3>
        <div class="card-tools"><span class="badge badge-<?= !empty($utilitys['locked']) ? 'warning' : 'success'; ?>"><?= !empty($utilitys['locked']) ? 'Approved version — locked' : 'Editable draft version'; ?></span></div>
    </div>
    <div class="card-body">
        <?php if (!empty($utilitys['locked'])) : ?>
            <div class="alert alert-info"><i class="fas fa-circle-info mr-1"></i>This version has approved calculations. Saving with a new effective month creates a new plan version and preserves previous results.</div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="plan_id" value="<?= (int) @$plan['id']; ?>">
            <h5 class="text-primary font-weight-bold">General Rules</h5>
            <div class="row">
                <div class="col-md-6 col-xl-4"><div class="form-group"><label>Plan Name</label><input type="text" class="form-control" name="plan_name" maxlength="100" value="<?= html_escape(@$plan['plan_name']); ?>" required></div></div>
                <div class="col-md-6 col-xl-2"><div class="form-group"><label>Effective Month</label><input type="month" class="form-control" name="effective_month" value="<?= html_escape(substr((string) @$plan['effective_from'], 0, 7)); ?>" required></div></div>
                <div class="col-md-4 col-xl-2"><div class="form-group"><label>Qualifying BV</label><input type="number" class="form-control" name="qualifying_bv" min="0" step="1" value="<?= html_escape(@$plan['qualifying_bv']); ?>" required></div></div>
                <div class="col-md-4 col-xl-2"><div class="form-group"><label>Initial Release %</label><input type="number" class="form-control" name="initial_release_percent" min="1" max="99" step="0.01" value="<?= html_escape(@$plan['initial_release_percent']); ?>" required></div></div>
                <div class="col-md-4 col-xl-2"><div class="form-group"><label>Pay Day</label><input type="number" class="form-control" name="pay_day" min="1" max="28" step="1" value="<?= html_escape(@$plan['pay_day']); ?>" required></div></div>
            </div>

            <hr><h5 class="text-primary font-weight-bold">Course BV and Initial Payment</h5>
            <div class="row incentive-rule-row">
                <div class="col-xl-6 d-flex mb-3">
                    <div class="card bg-light incentive-rule-card">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="font-weight-bold mb-0">B.Tech</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Course LIKE Filter</label>
                                        <input type="text" class="form-control text-uppercase" name="btech_keyword" value="<?= html_escape(@$btech['match_keyword'] ?: 'BTEC'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>BV / Admission</label>
                                        <input type="number" class="form-control" name="btech_bv" min="0.01" step="0.01" value="<?= html_escape(@$btech['bv']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Minimum Initial Payment</label>
                                        <input type="number" class="form-control" name="btech_initial_payment" min="0" step="0.01" value="<?= html_escape(@$btech['initial_payment']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="incentive-rule-note">
                                <i class="fas fa-info-circle mr-1"></i>Punctuation and spaces in the course code are ignored.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 d-flex mb-3">
                    <div class="card bg-light incentive-rule-card">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="font-weight-bold mb-0">Other</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>BV / Admission</label>
                                        <input type="number" class="form-control" name="other_bv" min="0.01" step="0.01" value="<?= html_escape(@$other['bv']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Initial Payment</label>
                                        <input type="number" class="form-control" name="other_initial_payment" min="0" step="0.01" value="<?= html_escape(@$other['initial_payment']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="incentive-rule-note">
                                <i class="fas fa-info-circle mr-1"></i>Used for every active course that does not match the B.Tech filter.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="font-weight-bold">Courses currently matched as B.Tech</h6>
            <div class="mb-3">
                <?php if (!empty($utilitys['matching_courses'])) : foreach ($utilitys['matching_courses'] as $course) : ?>
                    <span class="badge badge-light border mr-1 mb-1"><?= html_escape($course['course_code'] . ' — ' . $course['course_name']); ?></span>
                <?php endforeach; else : ?><span class="text-danger small">No active course currently matches this filter.</span><?php endif; ?>
            </div>

            <hr><div class="d-flex justify-content-between align-items-center"><h5 class="text-primary font-weight-bold mb-0">Progressive Slabs</h5><button type="button" class="btn btn-sm btn-outline-primary" id="add-incentive-slab"><i class="fas fa-plus mr-1"></i>Add Slab</button></div>
            <div class="table-responsive mt-2"><table class="table table-sm" id="incentive-slabs-table"><thead><tr><th style="width:28%">From BV</th><th style="width:28%">To BV</th><th style="width:34%">Rate / BV</th><th></th></tr></thead><tbody>
                <?php foreach ($slabs as $index => $slab) : ?>
                    <tr><td><input type="number" class="form-control" name="slab_from[]" min="0" step="1" value="<?= html_escape($slab['from_bv']); ?>" required></td><td><input type="number" class="form-control" name="slab_to[]" min="0" step="1" value="<?= html_escape($slab['to_bv']); ?>" placeholder="No limit"></td><td><input type="number" class="form-control" name="slab_rate[]" min="0" step="0.01" value="<?= html_escape($slab['rate_per_bv']); ?>" required></td><td class="text-right"><button type="button" class="btn btn-link text-danger remove-incentive-slab"><i class="fas fa-trash"></i></button></td></tr>
                <?php endforeach; ?>
            </tbody></table></div>
            <small class="text-muted">Ranges must be contiguous. Leave “To BV” empty only on the final slab.</small>
            <button type="submit" id="form-incentive-setup" style="display:none"></button>
        </form>
    </div>
</div>

<?php if (!empty($utilitys['versions'])) : ?>
<div class="card"><div class="card-header border-0"><h3 class="card-title font-weight-bold">Plan Versions</h3></div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Plan</th><th>Effective From</th><th>Effective To</th><th>Status</th></tr></thead><tbody><?php foreach ($utilitys['versions'] as $version) : ?><tr><td><?= html_escape($version['plan_name']); ?></td><td><?= html_escape($version['effective_from']); ?></td><td><?= html_escape($version['effective_to'] ?: 'Current'); ?></td><td><span class="badge badge-success"><?= html_escape($version['status']); ?></span></td></tr><?php endforeach; ?></tbody></table></div></div>
<?php endif; ?>
