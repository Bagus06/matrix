<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-md-4 col-xl-4">
                    <span class="text-primary font-weight-bold text-lg">Basic</span>
                    <div class="form-group">
                        <label id="lbl-source_code">Source Code</label>
                        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="source_code" minlength="0" maxlength="100" value="<?= @$utilitys['data']['source_code'] ?>" readonly required="true">
                        <small class="text-danger pl-3" id="err-source_code" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-source_name">Source <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" title="Select university for course" name="source_name" <?= ((!empty(@$utilitys['data']['source_name'])) ? 'readonly' : ''); ?> required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['sources'])) : ?>
                                <?php foreach ($utilitys['sources'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['source_name'] == $value['source_name']) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $key; ?>" <?= $selected ?>><?= $value['source_name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-source_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-url">URL</label>
                        <textarea type="text" class="form-control bg-transparant" name="url" value="<?= @$utilitys['data']['url'] ?>"><?= @$utilitys['data']['url'] ?></textarea>
                        <small class="text-danger pl-3" id="err-url" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-account">Account</label>
                        <input type="text" class="form-control bg-transparant" name="account" minlength="0" maxlength="100" value="<?= @$utilitys['data']['account'] ?>">
                        <small class="text-danger pl-3" id="err-account" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-password">Password</label>
                        <input type="password" class="form-control bg-transparant" name="password" minlength="0" maxlength="100" value="">
                        <small class="text-danger pl-3" id="err-password" style="display: none;"></small>
                    </div>
                </div>
                <div class="col-md-12 col-md-4 col-xl-4" id="source-detailed">
                    <span class="text-primary font-weight-bold text-lg">Detailed</span>
                    <div class="form-group">
                        <label id="lbl-b2b_company_name">Company Name</label>
                        <input type="text" class="form-control bg-transparant" name="b2b_company_name" minlength="0" maxlength="100" value="<?= @$utilitys['data']['b2b_company_name'] ?>">
                        <small class="text-danger pl-3" id="err-b2b_company_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-ref_name">Referance Name</label>
                        <input type="text" class="form-control bg-transparant" name="ref_name" minlength="0" maxlength="100" value="<?= @$utilitys['data']['ref_name'] ?>">
                        <small class="text-danger pl-3" id="err-ref_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-address">Address</label>
                        <textarea type="text" class="form-control bg-transparant" name="address" value="<?= @$utilitys['data']['address'] ?>"><?= @$utilitys['data']['address'] ?></textarea>
                        <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-discount">Discount (Percent)</label>
                        <input type="number" class="form-control bg-transparant" name="discount" min="0" max="100" value="<?= @$utilitys['data']['discount'] ?>">
                        <small class="text-danger pl-3" id="err-discount" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-email">Email</label>
                        <input type="email" class="form-control bg-transparent" placeholder="yourname@example.com" minlength="6" maxlength="64" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>">
                        <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-phone">Phone</label>
                        <input type="tel" class="form-control bg-transparent" name="phone" placeholder="91xxxxxxxxxx" minlength="10" maxlength="12" title="Enter a valid Indian phone number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['phone'] ?>">
                        <small class="text-danger pl-3" id="err-phone" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>