<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form action="" id="<?= ((empty($internal['create_form'])) ? $internal['edit_form'] : $internal['create_form']) ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label id="lbl-method_code">Method Code <label class="text-danger">*</label></label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="method_code" placeholder="Example: GP1, GP_1" title="Only letters, numbers, and underscore (_). No spaces or special characters allowed." pattern="^[A-Za-z0-9_]+$" minlength="3" maxlength="15" value="<?= @$utilitys['data']['method_code'] ?>" required>
        <small class="text-danger pl-3" id="err-method_code" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-method_name">Method Name <label class="text-danger">*</label></label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="method_name" minlength="3" maxlength="50" value="<?= @$utilitys['data']['method_name'] ?>" required>
        <small class="text-danger pl-3" id="err-method_name" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-category">Category</label>
        <select class="form-control bg-transparent" name="category">
            <option value="">--SELECT--</option>
            <?php if (!empty($utilitys['category'])) : ?>
                <?php foreach ($utilitys['category'] as $key => $value) : ?>
                    <?php
                    $selected = '';
                    if (@$utilitys['data']['category'] == $value) {
                        $selected = 'selected';
                    }
                    ?>
                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <small class="text-danger pl-3" id="err-category" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-account_name">Account <label class="text-danger">*</label></label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="account_name" minlength="3" maxlength="100" value="<?= @$utilitys['data']['account_name'] ?>" required>
        <small class="text-danger pl-3" id="err-account_name" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-account_identifier">Account Identifier <label class="text-danger">*</label></label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="account_identifier" minlength="3" maxlength="100" value="<?= @$utilitys['data']['account_identifier'] ?>" required>
        <small class="text-danger pl-3" id="err-account_identifier" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-bank_name">Bank Name</label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="bank_name" minlength="0" maxlength="100" value="<?= @$utilitys['data']['bank_name'] ?>">
        <small class="text-danger pl-3" id="err-bank_name" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-branch_name">Branch Name</label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="branch_name" minlength="0" maxlength="100" value="<?= @$utilitys['data']['branch_name'] ?>">
        <small class="text-danger pl-3" id="err-branch_name" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-ifsc_code">IFSC Code</label>
        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="ifsc_code" minlength="0" maxlength="100" value="<?= @$utilitys['data']['ifsc_code'] ?>">
        <small class="text-danger pl-3" id="err-ifsc_code" style="display: none;"></small>
    </div>
    <div class="form-group">
        <label id="lbl-status">Status</label>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((@$utilitys['data']['status'] === 'ACTIVE') ? 'checked' : '') ?>>
            <label for="status" class="custom-control-label">Payment method status.</label>
        </div>
        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
    </div>
</form>