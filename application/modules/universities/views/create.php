<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-md-4 col-xl-4">
                    <div class="form-group">
                        <label id="lbl-university_name">University Name <label class="text-danger mb-0">*</label></label>
                        <div class="col-12 row p-0 m-0">
                            <div class="col-8 p-0 m-0">
                                <input type="text" class="form-control bg-transparent" minlength="4" maxlength="100" name="university_name" placeholder="University Name" value="<?= @$utilitys['data']['university_name'] ?>" required>
                            </div>
                            <div class="col-4 pr-0">
                                <button type="button" class="btn btn-block btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="logo" data-modaltitle="Upload Logo" data-accept="image" data-fileurl="<?= base_url() . 'uploads/universities_logo/' . @$utilitys['data']['logo'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/photo/' . @$utilitys['data']['logo'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-building-columns"></i> Logo</button>
                                <input type="file" name="logo" hidden>
                                <input type="hidden" name="remove_logo" value="0">
                            </div>
                            <small class="text-danger pl-3" id="err-university_name" style="display: none;"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="lbl-short_name">University Short Name</label>
                        <input type="text" class="form-control bg-transparent" minlength="2" minlength="50" name="short_name" value="<?= @$utilitys['data']['short_name'] ?>">
                        <small class="text-danger pl-3" id="err-short_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-university_type">University Type</label>
                        <select class="form-control bg-transparent" name="university_type">
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['university_type'])) : ?>
                                <?php foreach ($utilitys['university_type'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['university_type'] == $value) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-university_type" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-ugc_code">UGC Code</label>
                        <input type="text" class="form-control bg-transparent" style="text-transform: uppercase;" name="ugc_code" minlength="5" maxlength="50" value="<?= @$utilitys['data']['ugc_code'] ?>">
                        <small class="text-danger pl-3" id="err-ugc_code" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-aicte_code">AICTE Code</label>
                        <input type="text" class="form-control bg-transparent" style="text-transform: uppercase;" name="aicte_code" minlength="5" maxlength="50" value="<?= @$utilitys['data']['aicte_code'] ?>">
                        <small class="text-danger pl-3" id="err-aicte_code" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-naac_grade">NAAC Grade <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" title="Select NAAC Grade" name="naac_grade" required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['naac_grade'])) : ?>
                                <?php foreach ($utilitys['naac_grade'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['naac_grade'] == $value) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-naac_grade" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-status">Status</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((@$utilitys['data']['status'] === 'ACTIVE') ? 'checked' : '') ?>>
                            <label for="status" class="custom-control-label">University status.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                </div>
                <div class="col-md-12 col-md-4  col-xl-4">
                    <div class="form-group">
                        <label id="lbl-contact">Contact <label class="text-danger mb-0">*</label></label>
                        <input type="tel" class="form-control bg-transparent" name="contact" placeholder="91xxxxxxxxxx" minlength="10" maxlength="12" title="Enter a valid Indian contact number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['contact'] ?>" required>
                        <small class="text-danger pl-3" id="err-contact" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-email">Email <label class="text-danger mb-0">*</label></label>
                        <input type="email" class="form-control bg-transparent" placeholder="yourname@example.com" minlength="6" maxlength="64" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>" required>
                        <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-website">Website</label>
                        <input type="text" class="form-control bg-transparent" name="website" placeholder="www.example.com" minlength="4" maxlength="253" pattern="^(?=.{4,253}$)(?!-)(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[A-Za-z]{2,63}$" value="<?= @$utilitys['data']['website'] ?>">
                        <small class="text-danger pl-3" id="err-website" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-description">Description</label>
                        <textarea type="text" class="form-control bg-transparent" name="description" value="<?= @$utilitys['data']['description'] ?>"><?= @$utilitys['data']['description'] ?></textarea>
                        <small class="text-danger pl-3" id="err-description" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-note">Note</label>
                        <textarea type="text" class="form-control bg-transparent" name="note" value="<?= @$utilitys['data']['note'] ?>"><?= @$utilitys['data']['note'] ?></textarea>
                        <small class="text-danger pl-3" id="err-note" style="display: none;"></small>
                    </div>
                </div>
                <div class="col-md-12 col-md-4  col-xl-4">
                    <div class="form-group">
                        <label id="lbl-country_id">Country</label>
                        <select class="form-control bg-transparent" title="Select country" name="country_id">
                            <option value="<?= @$utilitys['data']['country_id']; ?>" selected="selected"><?= @$utilitys['data']['country']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-country_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-state_id">State</label>
                        <select class="form-control bg-transparent" title="Select state" name="state_id">
                            <option value="<?= @$utilitys['data']['state_id']; ?>" selected="selected"><?= @$utilitys['data']['state']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-state_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-city_id">Regency / City</label>
                        <select class="form-control bg-transparent" title="Select state" name="city_id">
                            <option value="<?= @$utilitys['data']['city_id']; ?>" selected="selected"><?= @$utilitys['data']['city']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-city_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-district_id">Subdistrict</label>
                        <select class="form-control bg-transparent" title="Select state" name="district_id">
                            <option value="<?= @$utilitys['data']['district_id']; ?>" selected="selected"><?= @$utilitys['data']['district']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-district_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-address">Address</label>
                        <textarea type="text" class="form-control bg-transparent" rows="3" pattern="^[A-Za-z0-9\s.,#\/\-]{5,100}$" minlength="5" maxlength="100" placeholder="House No. 12, Green Street" name="address" value="<?= @$utilitys['data']['address'] ?>"><?= @$utilitys['data']['address'] ?></textarea>
                        <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-postal_code">Postal Code</label>
                        <input type="number" class="form-control bg-transparent" pattern="^[0-9]{4,6}$" inputmode="numeric" minlength="4" maxlength="6" placeholder="Postal Code" name="postal_code" value="<?= @$utilitys['data']['postal_code'] ?>">
                        <small class="text-danger pl-3" id="err-postal_code" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>