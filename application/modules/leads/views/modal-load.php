<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="uploadModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Aadhar document upload</h3>
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