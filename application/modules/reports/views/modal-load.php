<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="counselingReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title mb-1">Counseling & Admissions Report</h3>
                    <small class="text-muted">Daily activity, monthly tracking, admissions, and receivables in one PDF.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="counseling-report-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="counseling-report-date">Daily report date</label>
                                <input id="counseling-report-date" type="date" class="form-control" name="report_date" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="counseling-tracking-month">Monthly tracking period</label>
                                <input id="counseling-tracking-month" type="month" class="form-control" name="tracking_month" value="<?= date('Y-m', strtotime('first day of last month')); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-light border mb-0 small">
                        <div class="font-weight-bold mb-1"><i class="fas fa-circle-info text-primary mr-1"></i> Metric definitions</div>
                        <div><b>Called</b>: each lead is counted once per counselor in the selected day or month, regardless of repeated calls.</div>
                        <div><b>Responded / No Response</b>: latest call result for that lead within the selected period. <b>Admissions</b>: actual student records linked to the lead enquiry number.</div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0 d-none" id="counseling-report-progress" role="status" aria-live="polite">
                        <i class="fas fa-circle-notch fa-spin mr-2"></i><span>Preparing PDF report...</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-outline-primary" data-pdf-action="preview"><i class="far fa-eye mr-1"></i> Preview</button>
                <button type="button" class="btn btn-primary" data-pdf-action="download"><i class="fas fa-download mr-1"></i> Download PDF</button>
            </div>
        </div>
    </div>
</div>
