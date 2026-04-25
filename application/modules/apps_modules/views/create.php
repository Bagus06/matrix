<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-xl-4">
                    <div class="form-group">
                        <label id="lbl-module_code">CODE</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">MD_</span>
                            </div>
                            <input type="text" class="form-control" minlength="3" maxlength="3" style="text-transform: uppercase;" name="module_code" value="<?= @$utilitys['data']['module_code'] ?>" required="true">
                            <small class="text-danger pl-3" id="err-module_code" style="display: none;"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="lbl-module_title">Title</label>
                        <input type="text" class="form-control form-control-border" minlength="4" maxlength="50" name="module_title" value="<?= @$utilitys['data']['module_title'] ?>" required="true">
                        <small class="text-danger pl-3" id="err-module_title" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-description">Description</label>
                        <textarea type="text" class="form-control form-control-border" rows="5" name="description" value="<?= @$utilitys['data']['description'] ?>"><?= @$utilitys['data']['description'] ?></textarea>
                        <small class="text-danger pl-3" id="err-description" style="display: none;"></small>
                    </div>
                    <div class="col-12 row">
                        <div class="form-group col-md-12 col-xl-6">
                            <label id="lbl-sys_lock">Lock</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input custom-control-input-default" name="sys_lock" id="sys_lock" <?= ((@$utilitys['data']['sys_lock']) ? 'checked' : '') ?>>
                                <label for="sys_lock" class="custom-control-label"><i class="fa-solid fa-lock text-primary"></i> System Lock.</label>
                            </div>
                            <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                        </div>
                        <div class="form-group col-md-12 col-xl-6">
                            <label id="lbl-status">Status</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((empty(@$utilitys['data']['status'])) ? 'checked' : ((@$utilitys['data']['status'] === 'ACTIVE') ? 'checked' : '')) ?>>
                                <label for="status" class="custom-control-label">Module Active.</label>
                            </div>
                            <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-primary"><b>Feature</b></h5>
                            <div class="table-responsive p-0">
                                <table class="table table-sm table-striped table-hover" style="width: 100%;" id="table-feature">
                                    <thead>
                                        <tr>
                                            <td style="width: 5%;">NO. </td>
                                            <td style="width: 10%;">CODE</td>
                                            <td style="width: 20%;">TITLE</td>
                                            <td style="width: 35%;">DESC</td>
                                            <td class="text-center" style="width: 20%;">STATUS</td>
                                            <td class="text-center" style="width: 20%;">LOCK</td>
                                            <td style="width: 10%;"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center text-muted" colspan="6">
                                                <i>Data is Empty.</i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="" class="btn btn-sm btn-default mr-2" id="btnGenerateDefaultFeature"><i class="fa-solid fa-gears"></i> Generate Default Feature</a>
                                <a href="" class="btn btn-sm btn-primary" id="btnAddFeature"><i class="fa-solid fa-plus"></i> Add</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>