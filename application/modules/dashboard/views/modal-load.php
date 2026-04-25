<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" data-backdrop="static" id="modal-create">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create</h3>
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
<div class="modal fade" data-backdrop="static" id="modal-edit">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit</h3>
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
                <button type="submit" form="<?= @$internal['edit_form']; ?>" class="btn btn-primary" align="right">Update</button>
            </div>
        </div>
    </div>
</div>