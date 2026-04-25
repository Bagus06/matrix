<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-md-4  col-xl-4">
                    <div class="form-group">
                        <label id="lbl-test">Test</label>
                        <input type="text" class="form-control form-control-border" name="test" value="<?= @$utilitys['data']['test'] ?>" required="true">
                        <small class="text-danger pl-3" id="err-test" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>