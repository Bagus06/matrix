<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-12 row pr-0">
    <div class="col-12 text-center">
        <span class="text-primary text-lg font-weight-bold">DETAILED STUDENT INFORMATION</span>
        <hr>
    </div>
    <div class="col-12 col-md-12 col-xl-7">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 pb-3">
                        <span class="text-lg font-weight-bold"><i class="fa-solid fa-circle-info"></i> BASIC INFORMATION</span>
                    </div>
                    <div class="col-12 d-flex justify-content-center pb-3">
                        <input type="file" id="file-input" accept="image/*" hidden>
                        <div class="avatar-frame" id="avatar-preview" aria-hidden="true">
                            <?php
                            if (!empty($utilitys['data'])) {

                                $profile = '.assets/img/profile/' .  @$utilitys['data']['photo'];

                                if (file_exists($profile)) {
                                    $profile = @$utilitys['data']['photo'];
                                } else {
                                    $profile = 'sample.png';
                                }
                            ?>
                                <img src="<?= base_url() ?>assets/img/profile/<?= @$profile; ?>">
                            <?php
                            } else {
                            ?>
                                <span class="avatar-placeholder" id="avatar-placeholder">+</span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-aadhaar_number">Aadhaar No. <label class="text-danger">*</label></label>
                        <input type="number" class="form-control bg-transparant w-100" inputmode="numeric" pattern="\d{12}" minlength="11" maxlength="12" autocomplete="off" name="aadhaar_number" value="<?= @$utilitys['data']['aadhaar_number'] ?>" required>
                        <small class="text-danger pl-3" id="err-aadhaar_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-first_name">First Name</label>
                        <input type="text" class="form-control bg-transparant w-100" style="text-transform: uppercase;" name="first_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['first_name'] ?>">
                        <small class="text-danger pl-3" id="err-first_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-last_name">Last Name</label>
                        <input type="text" class="form-control bg-transparant w-100" style="text-transform: uppercase;" name="last_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['last_name'] ?>">
                        <small class="text-danger pl-3" id="err-last_name" style="display: none;"></small>
                    </div>
                    <div class="col-12 pl-0">
                        <strong>Document</strong>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadAadhaar"><i class="fa-solid fa-address-card"></i> Aadhaar</button>
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadPhoto"><i class="fa-solid fa-image-portrait"></i> Photo</button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12 pb-3">
                        <span class="text-lg font-weight-bold"><i class="fa-solid fa-gears"></i> MORE INFORMATION</span>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-date_of_birth">Date of Birth <label class="text-danger">*</label></label>
                        <input type="date" class="form-control bg-transparant w-100" placeholder="YYYY-MM-DD" inputmode="numeric" pattern="^\d{4}-\d{2}-\d{2}$" autocomplete="bday" name="date_of_birth" value="<?= @@$utilitys['data']['date_of_birth'] ?>" required>
                        <small class="text-danger pl-3" id="err-date_of_birth" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-gender">Gender <label class="text-danger">*</label></label><br>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-male" name="gender" value="MALE" <?= ((@$utilitys['data']['gender'] === 'MALE') ? 'checked' : '') ?> required>
                            <label for="gender-male">
                                Male
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-female" name="gender" value="FEMALE" <?= ((@$utilitys['data']['gender'] === 'FEMALE') ? 'checked' : '') ?> required>
                            <label for="gender-female">
                                Female
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-other" name="gender" value="OTHER" <?= ((@$utilitys['data']['gender'] === 'OTHER') ? 'checked' : '') ?> required>
                            <label for="gender-other">
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-phone">Phone <label class="text-danger">*</label></label>
                        <input type="tel" class="form-control bg-transparant w-100" name="phone" placeholder="91xxxxxxxxxx" minlength="10" maxlength="13" title="Enter a valid Indian phone number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['phone'] ?>" required>
                        <small class="text-danger pl-3" id="err-phone" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-whatsapp_number">WhatsApp</label>
                        <input type="tel" class="form-control bg-transparant w-100" name="whatsapp_number" placeholder="91xxxxxxxxxx" minlength="10" maxlength="13" title="Enter a valid Indian WhatsApp number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['whatsapp_number'] ?>">
                        <small class="text-danger pl-3" id="err-whatsapp_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-email">Email <label class="text-danger">*</label></label>
                        <input type="email" class="form-control bg-transparant w-100" style="text-transform: lowercase;" placeholder="yourname@example.com" minlength="6" maxlength="100" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>" required>
                        <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-country_id">Country <label class="text-danger">*</label></label>
                        <select class="form-control bg-transparant w-100" title="Select country" name="country_id" required>
                            <option value="<?= @$utilitys['data']['country_id']; ?>" selected><?= @$utilitys['data']['country']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-country_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-state_id">State <label class="text-danger">*</label></label>
                        <select class="form-control bg-transparant w-100" title="Select state" name="state_id" required>
                            <option value="<?= @$utilitys['data']['state_id']; ?>" selected><?= @$utilitys['data']['state']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-state_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-city_id">Regency / City</label>
                        <select class="form-control bg-transparant w-100" title="Select state" name="city_id">
                            <option value="<?= @$utilitys['data']['city_id']; ?>" selected><?= @$utilitys['data']['city']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-city_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-district_id">Subdistrict</label>
                        <select class="form-control bg-transparant w-100" title="Select state" name="district_id">
                            <option value="<?= @$utilitys['data']['district_id']; ?>" selected><?= @$utilitys['data']['district']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-district_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-address">Address <label class="text-danger">*</label></label>
                        <textarea type="text" class="form-control bg-transparant w-100" rows="4" name="address" value="<?= @$utilitys['data']['address'] ?>" required><?= @$utilitys['data']['address'] ?></textarea>
                        <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id=" lbl-postal_code">Postal Code</label>
                        <input type="number" class="form-control bg-transparant w-100" pattern="^[0-9]{4,6}$" inputmode="numeric" minlength="4" maxlength="6" placeholder="Postal Code" name="postal_code" value="<?= @$utilitys['data']['postal_code'] ?>">
                        <small class="text-danger pl-3" id="err-postal_code" style="display: none;"></small>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12 pb-3">
                        <span class="text-lg font-weight-bold"><i class="fa-solid fa-user-graduate"></i> STUDENT INFORMATION</span>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-student_number">Student No. <label class="text-danger">*</label></label>
                        <input type="text" class="form-control bg-transparant w-100" name="student_number" value="<?= @@$utilitys['data']['student_number']; ?>" readonly required>
                        <small class="text-danger pl-3" id="err-student_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-university_id">University <label class="text-danger">*</label></label>
                        <select class="form-control bg-transparant w-100" title="Select university for course" name="university_id" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'disabled'); ?> required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['universities']['data']['data'])) : ?>
                                <?php foreach ($utilitys['universities']['data']['data'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['university_id'] == $value['id']) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value['id']; ?>" <?= $selected ?>><?= $value['university_name'] . ' ( ' . $value['short_name'] . ' )' ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-university_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-course_id">Course <label class="text-danger">*</label></label>

                        <select class="form-control bg-transparant w-100" title="Select course" name="course_id" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'disabled'); ?> required>
                            <option value="<?= @$utilitys['data']['course_id']; ?>" <?= ((!empty(@$utilitys['data']['course_id'])) ? 'selected' : '') ?>>--SELECT--</option>
                        </select>
                        <small class="text-danger pl-3" id="err-course_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="pb-0 mb-0" id="lbl-final_fees">Fees <label class="text-danger">*</label></label>
                        <input type="text" class="form-control bg-transparant w-100" name="final_fees" value="<?= @$utilitys['data']['final_fees']; ?>" readonly required>
                        <small class="text-danger pl-3" id="err-final_fees" style="display: none;"></small>
                    </div>
                    <div class="col-12 pl-0">
                        <strong>Document</strong>
                    </div>
                    <div class="col-12 pl-2 pr-2">
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC1"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 1</button>
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC2"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 2</button>
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC3"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 3</button>
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC4"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 4</button>
                    </div>
                </div>
            </div>
            <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
        </form>
        <div class="card">
            <div class="card-body">
                <div class="col-12 pb-3">
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-user-plus"></i> LEADS INFORMATION</span>
                </div>
                <div class="form-group col-12 row">
                    <label class="pb-2" id="lbl-source_code">Leads Source</label>
                    <select class="form-control bg-transparant w-100" name="source_code" disabled required>
                        <option value="">--SELECT--</option>
                        <?php if (!empty($utilitys['leads_sources']['data']['data'])) : ?>
                            <?php foreach ($utilitys['leads_sources']['data']['data'] as $key => $value) : ?>
                                <?php
                                $selected = '';
                                $source_name = '';
                                if (@$utilitys['data_leads']['data']['source_code'] == $value['source_code']) {
                                    $selected = 'selected';
                                    $source_name = $value['source_name'];
                                }
                                ?>
                                <option value="<?= $value['source_code']; ?>" <?= $selected ?>><?= $value['source_code'] . (($value['source_name'] === 'B2B') ? ' ( Company Name : ' . $value['b2b_company_name'] . ' )' : (($value['source_name'] === 'REFERANCE') ? ' ( Referance Name : ' . $value['ref_name'] . ' )' : '')) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-danger pl-3" id="err-source_code" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <textarea type="text" class="form-control bg-transparant w-100" rows="2" name="source_information" value="<?= @$utilitys['data_leads']['data']['source_information'] ?>" disabled><?= @$utilitys['data_leads']['data']['source_information'] ?></textarea>
                    <small class="text-danger pl-3" id="err-source_information" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label class="pb-2" id="lbl-priority">Priority</label>
                    <select class="form-control bg-transparant w-100" name="priority" <?= ((@$utilitys['data_leads']['data']['status'] === 'YES') ? 'readonly' : '') ?> disabled required>
                        <option value=""><span class="text-muted text-italic">Select an Option</span></option>
                        <?php if (!empty($utilitys['priority'])) : ?>
                            <?php foreach ($utilitys['priority'] as $key => $value) : ?>
                                <?php
                                $selected = '';
                                if (@$utilitys['data_leads']['data']['priority'] == $value) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?= $value; ?>" <?= $selected ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-danger pl-3" id="err-priority" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-follow_up_date">Follow up Date</label>
                    <input type="date" class="form-control bg-transparant" name="follow_up_date" value="<?= @$utilitys['data_leads']['data']['follow_up_date'] ?>" disabled required>
                    <small class="text-danger pl-3" id="err-follow_up_date" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label class="pb-2" id="lbl-status">Status</label>
                    <select class="form-control bg-transparant w-100" name="status" <?= ((@$utilitys['data_leads']['data']['status'] === 'YES') ? 'readonly' : '') ?> disabled required>
                        <option value=""><span class="text-muted text-light text-italic" style="color: #999;">Select an Option</span></option>
                        <?php if (!empty($utilitys['status'])) : ?>
                            <?php foreach ($utilitys['status'] as $key => $value) : ?>
                                <?php
                                $selected = '';
                                if (@$utilitys['data_leads']['data']['status'] == $value) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?= $value; ?>" <?= $selected ?>><?= $value ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-note">Note</label>
                    <textarea type="text" class="form-control bg-transparant" name="note" value="<?= @$utilitys['data_leads']['data']['note'] ?>" disabled><?= ((empty($utilitys['data_leads']['data']['note'])) ? 'Empty' : $utilitys['data_leads']['data']['note']) ?></textarea>
                    <small class="text-danger pl-3" id="err-note" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label class="pb-2" id="lbl-assigned_to">Assigned Agent</label>
                    <select class="form-control bg-transparant w-100" name="assigned_to" <?= ((!empty(@$utilitys['data_leads']['data']['assigned_to'])) ? 'readonly' : ''); ?> disabled required>
                        <option value=""><span class="text-muted text-light text-italic">Select an Option</span></option>
                        <?php if (!empty($utilitys['assigned']['data']['data'])) : ?>
                            <?php foreach ($utilitys['assigned']['data']['data'] as $key => $value) : ?>
                                <?php
                                $selected = '';
                                if (@$utilitys['data_leads']['data']['assigned_to'] == $value['user_id']) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?= $value['user_id']; ?>" <?= $selected ?>><?= $value['name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-danger pl-3" id="err-assigned_to" style="display: none;"></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-xl-5">
        <div class="card">
            <div class="card-body">
                <div class="col-12 pb-3">
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-wallet"></i> PAYMENT INFORMATION</span>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-status">Status</label>
                    <input type="text" class="form-control bg-transparant" name="status" value="<?= @$utilitys['data_payment']['data']['status'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-total_amount">Total Amount</label>
                    <input type="number" class="form-control bg-transparant" name="total_amount" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['total_amount'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-total_amount" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-discount_percent">Discount</label>
                    <input type="number" class="form-control bg-transparant" name="discount_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['discount_percent'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-discount_percent" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-aditional_discount_percent">Aditional Discount</label>
                    <input type="number" class="form-control bg-transparant" name="aditional_discount_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['aditional_discount_percent'] ?>" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'readonly'); ?>>
                    <small class="text-danger pl-3" id="err-aditional_discount_percent" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-total_discount_percent">Total Discount</label>
                    <input type="number" class="form-control bg-transparant" name="total_discount_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['total_discount_percent'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-total_discount_percent" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-tax_percent">TAX</label>
                    <input type="number" class="form-control bg-transparant" name="tax_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['tax_percent'] ?>" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'readonly'); ?>>
                    <small class="text-danger pl-3" id="err-tax_percent" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-final_amount">Final Amount</label>
                    <input type="number" class="form-control bg-transparant" name="final_amount" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['final_amount'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-final_amount" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-remaining_balance">Remaining Balance</label>
                    <input type="number" class="form-control bg-transparant" name="remaining_balance" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['remaining_balance'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-remaining_balance" style="display: none;"></small>
                </div>
                <hr>
                <div class="col-12 pb-3">
                    <span class="text-sm text-primary font-weight-bold">ADVANCE</span>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-invoice_number">Invoice Number</label>
                    <input type="text" class="form-control bg-transparant" name="invoice_number" value="<?= @$utilitys['data_payment']['data']['invoice_number'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-invoice_number" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-advance_percent">Advance Percent</label>
                    <input type="number" class="form-control bg-transparant" name="advance_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['advance_percent'] ?>" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'readonly'); ?>>
                    <small class="text-danger pl-3" id="err-advance_percent" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-advance_amount">Advance Amount</label>
                    <input type="number" class="form-control bg-transparant" name="advance_amount" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['advance_amount'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-advance_amount" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-advance_date">Advance Date</label>
                    <input type="date" class="form-control bg-transparant" name="advance_date" value="<?= @$utilitys['data_payment']['data']['advance_date'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-advance_date" style="display: none;"></small>
                </div>
                <div class="col-12 pl-3 pr-3 row">
                    <div class="col-6 p-0">
                        <button type="button" class="btn btn-outline-primary w-100" data-invoicestatus="<?= @$utilitys['data_invoice']['data']['approval_status']; ?>" id="btn-advence-invoice" <?= (((strtoupper(@$utilitys['data_invoice']['data']['approval_status']) !== 'APPROVED') && !user_group_check('GR_ADMIN', get_user()['id'])) ? 'disabled' : ''); ?>><?= ((strtoupper(@$utilitys['data_invoice']['data']['approval_status']) === 'APPROVED') ? 'Download Invoice' : 'Release Invoice'); ?></button>
                    </div>
                    <div class="col-6 p-0 pl-2">
                        <button class="btn btn-outline-primary w-100" disabled>Release Receipt</button>
                    </div>
                </div>
                <hr>
                <div class="col-12 pb-3">
                    <span class="text-sm text-primary font-weight-bold">FINAL PAYMENT</span>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-due_date">Final Payment Due Date</label>
                    <input type="date" class="form-control bg-transparant" name="due_date" value="<?= @$utilitys['data_payment']['data']['due_date'] ?>" <?= ((user_group_check('GR_ADMIN', get_user()['id']) && (@$utilitys['data_invoice']['data']['approval_status'] != 'APPROVED')) ? '' : 'readonly'); ?>>
                    <small class="text-danger pl-3" id="err-due_date" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-final_payment">Final Payment</label>
                    <input type="number" class="form-control bg-transparant" name="final_payment" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['data']['final_payment'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-final_payment" style="display: none;"></small>
                </div>
                <div class="form-group col-12 row">
                    <label id="lbl-final_payment_date">Final Payment Date</label>
                    <input type="date" class="form-control bg-transparant" name="final_payment_date" value="<?= @$utilitys['data_payment']['data']['final_payment_date'] ?>" readonly>
                    <small class="text-danger pl-3" id="err-final_payment_date" style="display: none;"></small>
                </div>
                <div class="col-12 pl-3 pr-3 row">
                    <button class="btn btn-outline-primary w-100" id="btn-receipt">Release Receipt</button>
                </div>
            </div>
        </div>
    </div>
</div>