<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form action="" id="<?= ((empty($internal['create_form'])) ? $internal['edit_form'] : $internal['create_form']) ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label id="lbl-test">Test</label>
        <input type="text" class="form-control form-control-border" name="test" value="<?= @$utilitys['data']['test'] ?>" required>
        <small class="text-danger pl-3" id="err-test" style="display: none;"></small>
    </div>
</form>