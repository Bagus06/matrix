<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" data-backdrop="static" id="editModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4>Edit Menu</h4>
                <div class="form-group">
                    <label id="lbl-display_title">Title</label>
                    <input type="text" class="form-control form-control-border" minlength="4" maxlength="50" name="display_title" value="<?= @$utilitys['data']['display_title'] ?>" required="true">
                    <small class="text-danger pl-3" id="err-display_title" style="display: none;"></small>
                </div>
                <div class="form-group">
                    <label id="lbl-icon">Icon</label>
                    <input type="text" class="form-control form-control-border" minlength="4" maxlength="50" name="icon" value="<?= @$utilitys['data']['icon'] ?>" required="true">
                    <small class="text-danger pl-3" id="err-icon" style="display: none;"></small>
                </div>
                <div class="form-group">
                    <label id="lbl-description">Description</label>
                    <textarea type="text" class="form-control form-control-border" rows="3" name="description" value="<?= @$utilitys['data']['description'] ?>"><?= @$utilitys['data']['description'] ?></textarea>
                    <small class="text-danger pl-3" id="err-description" style="display: none;"></small>
                </div>
                <div class="form-group">
                    <label id="lbl-url">URL</label>
                    <input type="text" class="form-control form-control-border" minlength="10" maxlength="30" name="url" value="<?= @$utilitys['data']['url'] ?>" required="true">
                    <small class="text-danger pl-3" id="err-url" style="display: none;"></small>
                </div>
                <div class="form-group">
                    <label id="lbl-feature_code">Feature</label>
                    <select class="form-control form-control-border" name="feature_code">
                        <option value="">--SELECT--</option>
                        <?php if (!empty($utilitys['features']['data'])) : ?>
                            <?php foreach ($utilitys['features']['data'] as $key => $feature) : ?>
                                <option value="<?= $feature['feature_code']; ?>"><?= $feature['feature_code'] . ' ( ' . $feature['module_title'] . ' - ' . $feature['feature_title'] . ' )' ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-danger pl-3" id="err-feature_code" style="display: none;"></small>
                </div>
                <div class="col-12 row">
                    <div class="form-group col-md-12 col-xl-4">
                        <label id="lbl-sys_lock">Lock</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="sys_lock" id="sys_lock" <?= ((@$utilitys['data']['sys_lock']) ? 'checked' : '') ?>>
                            <label for="sys_lock" class="custom-control-label"><i class="fa-solid fa-lock text-primary"></i> System Lock.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                    <div class="form-group col-md-12 col-xl-4">
                        <label id="lbl-status">Status</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((@$utilitys['data']['status']) ? 'checked' : '') ?>>
                            <label for="status" class="custom-control-label">Menu Active.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                    <div class="form-group col-md-12 col-xl-4">
                        <label id="lbl-visible">Visible</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="visible" id="visible" <?= ((empty(@$utilitys['data']['visible'])) ? 'checked' : ((@$utilitys['data']['visible'] === 'ACTIVE') ? 'checked' : '')) ?>>
                            <label for="visible" class="custom-control-label">Menu Visible.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-visible" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div></div>
                <button type="submit" class="btn btn-default pr-2" id="btnCancelEdit" align="right">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btnSaveEdit" align="right">Save</button>
            </div>
        </div>
    </div>
</div>