<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" data-backdrop="static" id="modal-advance-invoice">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Advance Invoice</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12 text-center">
                    <i class="text-muted">Empty.</i>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div></div>
                <button type="submit" class="btn btn-primary" align="right">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="static" id="modal-receipt">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Release Receipt</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12 row">
                    <div class="col-12">
                        <nav class="w-100">
                            <div class="nav nav-tabs" id="receipt-tab" role="tablist">
                                <a class="nav-item nav-link active" id="receipt-form-tab" data-toggle="tab" href="#receipt-form" role="tab" aria-controls="receipt-form" aria-selected="true">Receipt Form</a>
                                <a class="nav-item nav-link" id="receipt-print-tab" data-toggle="tab" href="#receipt-print" role="tab" aria-controls="receipt-print" aria-selected="false">Print Receipts</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="receipt-form" role="tabpanel" aria-labelledby="receipt-form-tab">
                                <div class="m-3">
                                    <table style="width:100%" border="0">
                                        <tr style="text-align: left;">
                                            <td style="text-align: left;">
                                                <label id="lbl-receipt_for">For</label><label class="text-danger">*</label>
                                            </td>
                                            <td>
                                                <select class="form-control bg-transparant" invy-required="true" name="receipt_for">
                                                    <option value="">--Select--</option>
                                                    <option value="down_payment">Down Payment</option>
                                                    <option value="partial_payment">Partial Payment</option>
                                                    <option value="final_payment">Final Payment</option>
                                                </select>
                                                <small class="text-danger pl-0" id="err-receipt_for" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td style="text-align: left;">
                                                <label id="lbl-receipt_installment">Installment No.</label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control bg-transparant" name="receipt_installment" min="1" value="" readonly>
                                                <small class="text-danger pl-0" id="err-receipt_installment" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_method">Payment Method</label><label class="text-danger">*</label>
                                            </td>
                                            <td>
                                                <select class="form-control bg-transparant" invy-required="true" name="receipt_method">
                                                    <option value="">--Select--</option>
                                                    <?php if (!empty($utilitys['payment_methods'])): ?>
                                                        <?php foreach ($utilitys['payment_methods'] as $key => $value): ?>
                                                            <option value="<?= @$value['id'] ?>"><?= @$value['method_name'] ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <small class="text-danger pl-0" id="err-receipt_method" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_total_amount">Total Emount</label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control bg-transparant" name="receipt_total_amount" inputmode="decimal" step="0.01" min="0" value="0" readonly>
                                                <small class="text-danger pl-0" id="err-receipt_total_amount" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-total_receipt_amount">Total Receipt Emount</label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control bg-transparant" name="total_receipt_amount" inputmode="decimal" step="0.01" min="0" value="" readonly>
                                                <small class="text-danger pl-0" id="err-total_receipt_amount" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_date">Receipt Date</label><label class="text-danger">*</label>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control bg-transparant" invy-required="true" name="receipt_date" value="<?= date('Y-m-d'); ?>">
                                                <small class="text-danger pl-0" id="err-receipt_date" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_amount">Receipt Emount</label><label class="text-danger">*</label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control bg-transparant" invy-required="true" name="receipt_amount" inputmode="decimal" step="0.01" min="0" max="<?= @$utilitys['data_payment']['data']['remaining_balance']; ?>" value="">
                                                <small class="text-danger pl-0" id="err-receipt_amount" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_remaining_balance">Remaining Balance</label>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control bg-transparant" name="receipt_remaining_balance" inputmode="decimal" step="0.01" min="0" value="0" readonly>
                                                <small class="text-danger pl-0" id="err-receipt_remaining_balance" style="display: none;"></small>
                                            </td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <td>
                                                <label id="lbl-receipt_note">Note</label>
                                            </td>
                                            <td>
                                                <textarea class="form-control bg-transparant" name="receipt_note"></textarea>
                                                <small class="text-danger pl-0" id="err-receipt_note" style="display: none;"></small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <hr>
                                <div class="row mt-3 justify-content-between">
                                    <div></div>
                                    <button type="submit" class="btn btn-primary" id="btn-release-receipt" align="right" disabled>Release Receipt</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="receipt-print" role="tabpanel" aria-labelledby="receipt-print-tab">
                                <div class="m-3">
                                    <div class="form-group">
                                        <label id="lbl-receipts">Receipts</label>
                                        <select class="form-control bg-transparant" name="receipt_options">
                                        </select>
                                        <small class="text-danger pl-0" id="err-receipts" style="display: none;"></small>
                                    </div>
                                    <div class="form-group">
                                        <label id="lbl-view_outstanding_balance">Outstanding Balance</label>
                                        <input type="text" class="form-control bg-transparant" name="view_outstanding_balance" readonly>
                                        <small class="text-danger pl-0" id="err-view_outstanding_balance" style="display: none;"></small>
                                    </div>
                                    <div class="form-group">
                                        <label id="lbl-view_receipt_amount">Receipt Amount</label>
                                        <input type="text" class="form-control bg-transparant" name="view_receipt_amount" readonly>
                                        <small class="text-danger pl-0" id="err-view_receipt_amount" style="display: none;"></small>
                                    </div>
                                    <div class="form-group">
                                        <label id="lbl-view_remaining_balance">Remaining Balance</label>
                                        <input type="text" class="form-control bg-transparant" name="view_remaining_balance" readonly>
                                        <small class="text-danger pl-0" id="err-view_remaining_balance" style="display: none;"></small>
                                    </div>
                                    <div class="form-group">
                                        <label id="lbl-view_receipt_date">Receipt Date</label>
                                        <input type="text" class="form-control bg-transparant" name="view_receipt_date" readonly>
                                        <small class="text-danger pl-0" id="err-view_receipt_date" style="display: none;"></small>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-3 justify-content-between">
                                    <div></div>
                                    <button type="submit" class="btn btn-primary" id="btn-print-receipt" data-receiptnumber="" data-studentnumber="" align="right"><i class="fa-solid fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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