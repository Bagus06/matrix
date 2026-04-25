<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-md-4  col-xl-6">
                    <div class="form-group">
                        <label id="lbl-group_code">CODE</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">GR_</span>
                            </div>
                            <input type="text" class="form-control" minlength="5" maxlength="5" style="text-transform: uppercase;" name="group_code" value="<?= @$utilitys['data']['group_code'] ?>" required="true">
                            <small class="text-danger pl-3" id="err-group_code" style="display: none;"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="lbl-group_title">Title</label>
                        <input type="text" class="form-control form-control-border" minlength="4" maxlength="50" name="group_title" value="<?= @$utilitys['data']['group_title'] ?>" required="true">
                        <small class="text-danger pl-3" id="err-group_title" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-description">Description</label>
                        <textarea type="text" class="form-control form-control-border" rows="5" name="description" value="<?= @$utilitys['data']['description'] ?>"><?= @$utilitys['data']['description'] ?></textarea>
                        <small class="text-danger pl-3" id="err-description" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-feature">Feature</label>
                        <select class="form-control form-control-border select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="features[]">
                            <option value="">--Select--</option>
                            <?php if (!empty($utilitys['features']['data']['data'])) : ?>
                                <?php foreach ($utilitys['features']['data']['data'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (in_array($value['feature_code'], @$utilitys['group_relations'])) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value['feature_code'] ?>" <?= $selected; ?>><?= $value['feature_code'] ?> (<?= $value['module_title']  . ' - ' . $value['feature_title'] ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-feature" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-status">Status</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((@$utilitys['data']['status'] === 'ACTIVE') ? 'checked' : '') ?>>
                            <label for="status" class="custom-control-label"><i class="fa-solid fa-lock text-primary"></i> Group Status.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>