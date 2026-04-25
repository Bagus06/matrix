<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <h3 class="text-primary"><?= (($this->uri->rsegments[2] === 'create') ? 'Create' : 'Edit') ?></h3>
            <hr>
            <div class="col-12 row">
                <div class="col-md-12 col-md-4 col-xl-4">
                    <div class="form-group">
                        <label id="lbl-university_id">University <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" title="Select university for course" name="university_id" required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['universities']['data']['data'])) : ?>
                                <?php foreach ($utilitys['universities']['data']['data'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['university_id'] == $value['id']) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value['id']; ?>" <?= $selected ?>><?= $value['university_name'] . ((!empty($value['short_name'])) ? '( ' . $value['short_name'] . ' )' : '')  ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-university_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-course_name">Course Name <label class="text-danger mb-0">*</label></label>
                        <input type="text" class="form-control bg-transparant" name="course_name" minlength="5" maxlength="100" value="<?= @$utilitys['data']['course_name'] ?>" required>
                        <small class="text-danger pl-3" id="err-course_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-course_code">Code</label>
                        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="course_code" minlength="2" maxlength="20" value="<?= @$utilitys['data']['course_code'] ?>">
                        <small class="text-danger pl-3" id="err-course_code" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-course_type">Course Type <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" title="Select university for course" name="course_type" required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['course_type'])) : ?>
                                <?php foreach ($utilitys['course_type'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (strtoupper(@$utilitys['data']['course_type']) == strtoupper($value)) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value;  ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-course_type" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-course_level">Level <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" title="Select university for course" name="course_level" required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['course_level'])) : ?>
                                <?php foreach ($utilitys['course_level'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (strtoupper(@$utilitys['data']['course_level']) == strtoupper($value)) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value;  ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-course_type" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-duration_year">Duration (Year) <label class="text-danger mb-0">*</label></label>
                        <input type="number" class="form-control bg-transparant" name="duration_year" step="0.01" min="0" placeholder="Duration year" inputmode="decimal" value="<?= @$utilitys['data']['duration_year'] ?>" required>
                        <small class="text-danger pl-3" id="err-duration_year" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-total_semesters">Total Semesters <label class="text-danger mb-0">*</label></label>
                        <input type="number" class="form-control bg-transparant" name="total_semesters" min="2" max="13" value="<?= @$utilitys['data']['total_semesters'] ?>" required>
                        <small class="text-danger pl-3" id="err-total_semesters" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-status">Status</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input custom-control-input-default" name="status" id="status" <?= ((@$utilitys['data']['status'] === 'ACTIVE') ? 'checked' : '') ?>>
                            <label for="status" class="custom-control-label">Course status.</label>
                        </div>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                </div>
                <div class="col-md-12 col-md-4 col-xl-4">
                    <div class="form-group">
                        <label id="lbl-fee">Course Fee <label class="text-danger mb-0">*</label></label>
                        <input type="number" class="form-control bg-transparant" name="fee" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data']['fee'] ?>" required>
                        <small class="text-danger pl-3" id="err-fee" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-markup_fee_percent">Markup Fee (Percent) <label class="text-danger mb-0">*</label></label>
                        <input type="number" class="form-control bg-transparant" name="markup_fee_percent" min="0" max="100" value="<?= @$utilitys['data']['markup_fee_percent'] ?>" required>
                        <small class="text-danger pl-3" id="err-markup_fee_percent" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-final_fee">Final fee not include discount <label class="text-danger mb-0">*</label></label>
                        <input type="number" class="form-control bg-transparant" name="final_fee" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data']['final_fee'] ?>" readonly required>
                        <small class="text-danger pl-3" id="err-final_fee" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-discount_percent">Discount (Percent)</label>
                        <input type="number" class="form-control bg-transparant" name="discount_percent" min="0" max="100" value="<?= @$utilitys['data']['discount_percent'] ?>">
                        <small class="text-danger pl-3" id="err-discount_percent" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-discount_date_periode">Discount validity period</label>
                        <input type="text" class="form-control bg-transparant" name="discount_date_periode" value="<?= @$utilitys['data']['discount_date_periode'] ?>">
                        <small class="text-danger pl-3" id="err-discount_date_periode" style="display: none;"></small>
                    </div>
                </div>
                <div class="col-md-12 col-md-4 col-xl-4">
                    <div class="form-group">
                        <label id="lbl-eligibility">Eligibility</label>
                        <textarea type="text" class="form-control bg-transparant" name="eligibility" value="<?= @$utilitys['data']['eligibility'] ?>"><?= @$utilitys['data']['eligibility'] ?></textarea>
                        <small class="text-danger pl-3" id="err-eligibility" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-description">Description</label>
                        <textarea type="text" class="form-control bg-transparant" name="description" value="<?= @$utilitys['data']['description'] ?>"><?= @$utilitys['data']['description'] ?></textarea>
                        <small class="text-danger pl-3" id="err-description" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
    </div>
</div>