<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="uploadModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">-</h3>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

                <div class="dropZone text-center p-4 mb-2">
                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                    <p class="mb-1"><b>Drag & Drop file here</b></p>
                    <p class="text-muted small mb-0">or click to select file</p>
                </div>

                <p class="text-muted mb-0 pb-0 fileInfoText">Allowed: JPG, PNG, PDF</p>

                <!-- INPUT FILE DI MODAL (sementara) -->
                <input type="file" class="fileTemp" accept="image/*,application/pdf" hidden>

                <!-- PREVIEW -->
                <div style="display:none;" class="mt-0 previewContainer">

                    <img class="img-fluid img-thumbnail mb-2 imagePreview" style="max-height:300px; display:none;">

                    <div class="pdfPreview" style="display:none;">
                        <button type="button" class="btn btn-link btnViewPdf">
                            <i class="fas fa-file-pdf"></i> View Document
                        </button>
                    </div>

                </div>
                <button type="button" class="btn btn-sm btn-link btnRemoveFile mt-2" style="display:none;">
                    <i class="fas fa-trash"></i> Remove File
                </button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentReportModal" aria-hidden="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">-</h3>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="col-12 row p-0 m-0">
                    <div class="col-12 p-0 m-0">
                        <label>Select Date</label>
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="report_for" value="">
                        <div class="form-group">
                            <label id="lbl-date_start">Start</label>
                            <input type="date" class="form-control bg-transparent" name="date_start" value="<?= date('Y-m-d', strtotime('-1 month')) ?>">
                            <small class="text-danger pl-3" id="err-date_start" style="display: none;"></small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label id="lbl-date_end">End</label>
                            <input type="date" class="form-control bg-transparent" name="date_end" value="<?= date('Y-m-d') ?>">
                            <small class="text-danger pl-3" id="err-date_end" style="display: none;"></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-primary mr-2" style="width: 150px;" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="submit-report" style="width: 150px;">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>