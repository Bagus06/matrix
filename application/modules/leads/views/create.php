<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form method="post" action="" enctype="multipart/form-data">
    <div class="col-12 p-0 m-0 row">
        <div class="col-12 col-md-12 col-xl-7">
            <div class="card">
                <div class="card-body">
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-circle-info"></i> BASIC INFORMATION</span>
                    <hr>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-enquiry_number">Enquiry No. <label class="text-danger">*</label></label>
                        <input type="text" class="form-control bg-transparant" name="enquiry_number" value="<?= @$utilitys['data']['enquiry_number'] ?>" readonly required>
                        <small class="text-danger pl-3" id="err-enquiry_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-first_name">First Name <label class="text-danger">*</label></label>
                        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="first_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['first_name'] ?>" required>
                        <small class="text-danger pl-3" id="err-first_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-last_name">Last Name</label>
                        <input type="text" class="form-control bg-transparant" style="text-transform: uppercase;" name="last_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['last_name'] ?>">
                        <small class="text-danger pl-3" id="err-last_name" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-phone">Phone <label class="text-danger">*</label></label>
                        <input type="tel" class="form-control bg-transparent" name="phone" minlength="7" maxlength="15" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['phone'] ?>" required>
                        <small class="text-danger pl-3" id="err-phone" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-whatsapp_number">WhatsApp</label>
                        <input type="tel" class="form-control bg-transparent" name="whatsapp_number" minlength="7" maxlength="15" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['whatsapp_number'] ?>">
                        <small class="text-danger pl-3" id="err-whatsapp_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-email">Email <label class="text-danger">*</label></label>
                        <input type="email" class="form-control bg-transparent" style="text-transform: lowercase;" placeholder="yourname@example.com" minlength="6" maxlength="100" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>" required>
                        <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                    </div>
                    <div class="col-12 pl-0">
                        <strong>Document</strong>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_aadhaar" data-modaltitle="Upload Aadhar" data-accept="image,pdf" data-fileurl="<?= base_url() . 'uploads/aadhaar/' . @$utilitys['data']['file_aadhaar'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/aadhaar/' . @$utilitys['data']['file_aadhaar'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-address-card"></i> Aadhaar</button>
                        <input type="file" name="file_aadhaar" hidden>
                        <input type="hidden" name="remove_file_aadhaar" value="0">

                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_photo" data-modaltitle="Upload Photo" data-accept="image" data-fileurl="<?= base_url() . 'uploads/photo/' . @$utilitys['data']['file_photo'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/photo/' . @$utilitys['data']['file_photo'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-image-portrait"></i> Photo</button>
                        <input type="file" name="file_photo" hidden>
                        <input type="hidden" name="remove_file_photo" value="0">
                    </div>
                    <hr>
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-gears"></i> MORE INFORMATION</span>
                    <hr>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control bg-transparent" placeholder="YYYY-MM-DD" inputmode="numeric" pattern="^\d{4}-\d{2}-\d{2}$" autocomplete="bday" name="date_of_birth" value="<?= ((!empty($utilitys['data_student']['date_of_birth'])) ? $utilitys['data_student']['date_of_birth'] : @$utilitys['data']['date_of_birth']) ?>">
                        <small class="text-danger pl-3" id="err-date_of_birth" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-aadhaar_number">Aadhaar No.</label>
                        <input type="number" class="form-control bg-transparent" inputmode="numeric" pattern="\d{12}" minlength="11" maxlength="12" autocomplete="off" name="aadhaar_number" value="<?= ((!empty($utilitys['data_student']['aadhaar_number'])) ? $utilitys['data_student']['aadhaar_number'] : @$utilitys['data']['aadhaar_number']) ?>">
                        <small class="text-danger pl-3" id="err-aadhaar_number" style="display: none;"></small>
                    </div>
                    <div class="form-group clearfix">
                        <label class="mb-0" id="lbl-gender">Gender</label>
                        <br>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-male" name="gender" value="MALE" <?= ((((!empty($utilitys['data_student']['gender'])) ? $utilitys['data_student']['gender'] : @$utilitys['data']['gender']) === 'MALE') ? 'checked' : '') ?>>
                            <label for="gender-male">
                                Male
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-female" name="gender" value="FEMALE" <?= ((((!empty($utilitys['data_student']['gender'])) ? $utilitys['data_student']['gender'] : @$utilitys['data']['gender']) === 'FEMALE') ? 'checked' : '') ?>>
                            <label for="gender-female">
                                Female
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="gender-other" name="gender" value="OTHER" <?= ((((!empty($utilitys['data_student']['gender'])) ? $utilitys['data_student']['gender'] : @$utilitys['data']['gender']) === 'OTHER') ? 'checked' : '') ?>>
                            <label for="gender-other">
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-country_id">Country</label>
                        <select class="form-control bg-transparent" title="Select country" name="country_id">
                            <option value="<?= @$utilitys['data']['country_id']; ?>" selected><?= @$utilitys['data']['country']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-country_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-state_id">State</label>
                        <select class="form-control bg-transparent" title="Select state" name="state_id">
                            <option value="<?= @$utilitys['data']['state_id']; ?>" selected><?= @$utilitys['data']['state']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-state_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-city_id">Regency / City</label>
                        <select class="form-control bg-transparent" title="Select state" name="city_id">
                            <option value="<?= @$utilitys['data']['city_id']; ?>" selected><?= @$utilitys['data']['city']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-city_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-district_id">Subdistrict</label>
                        <select class="form-control bg-transparent" title="Select state" name="district_id">
                            <option value="<?= @$utilitys['data']['district_id']; ?>" selected><?= @$utilitys['data']['district']; ?></option>
                        </select>
                        <small class="text-danger pl-3" id="err-district_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-address">Address</label>
                        <textarea type="text" class="form-control bg-transparent" rows="4" name="address" value="<?= @$utilitys['data']['address'] ?>"><?= @$utilitys['data']['address'] ?></textarea>
                        <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-postal_code">Postal Code</label>
                        <input type="number" class="form-control bg-transparent" pattern="^[0-9]{4,6}$" inputmode="numeric" minlength="4" maxlength="6" placeholder="Postal Code" name="postal_code" value="<?= @$utilitys['data']['postal_code'] ?>">
                        <small class="text-danger pl-3" id="err-postal_code" style="display: none;"></small>
                    </div>
                    <hr>
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-user-graduate"></i> STUDENT INFORMATION</span>
                    <hr>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-student_number">Student No.</label>
                        <input type="text" class="form-control bg-transparent" name="student_number" value="<?= @$utilitys['data_student']['student_number']; ?>" required="true" readonly>
                        <small class="text-danger pl-3" id="err-student_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-university_id">University</label>
                        <select class="form-control bg-transparent" title="Select university for course" name="university_id">
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
                        <label class="mb-0" id="lbl-course_id">Course</label>
                        <select class="form-control bg-transparent" title="Select course" name="course_id">
                            <option value="<?= @$utilitys['data']['course_id']; ?>" <?= ((!empty(@$utilitys['data']['course_id'])) ? 'selected' : '') ?>>--SELECT--</option>
                        </select>
                        <small class="text-danger pl-3" id="err-course_id" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-additional_certificate">Additional certificate or attestation</label>
                        <textarea type="text" class="form-control bg-transparent" rows="2" name="additional_certificate" value="<?= @$utilitys['data_student']['additional_certificate'] ?>"><?= @$utilitys['data_student']['additional_certificate'] ?></textarea>
                        <small class="text-danger pl-3" id="err-additional_certificate" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label id="lbl-additional_certificate_fee">Additional certificate or attestation fee</label>
                        <input type="number" class="form-control bg-transparant" name="additional_certificate_fee" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data_student']['additional_certificate_fee'] ?>">
                        <small class="text-danger pl-3" id="err-additional_certificate_fee" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-final_fees">Fees</label>
                        <input type="text" class="form-control bg-transparent" name="final_fees" value="<?= @$utilitys['data_student']['final_fees']; ?>" readonly>
                        <small class="text-danger pl-3" id="err-final_fees" style="display: none;"></small>
                    </div>
                    <div class="col-12 pl-0">
                        <strong>Document</strong>
                    </div>
                    <div class="col-12 pl-2 pr-2">
                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_certificate1" data-modaltitle="Upload Certificate 1" data-accept="image,pdf" data-fileurl="<?= base_url() . 'uploads/certificate1/' . @$utilitys['data']['file_certificate1'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/certificate1/' . @$utilitys['data']['file_certificate1'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-address-card"></i> Certificate 1</button>
                        <input type="file" name="file_certificate1" hidden>
                        <input type="hidden" name="remove_file_certificate1" value="0">

                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_certificate2" data-modaltitle="Upload Certificate 2" data-accept="image,pdf" data-fileurl="<?= base_url() . 'uploads/certificate2/' . @$utilitys['data']['file_certificate2'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/certificate2/' . @$utilitys['data']['file_certificate2'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-address-card"></i> Certificate 2</button>
                        <input type="file" name="file_certificate2" hidden>
                        <input type="hidden" name="remove_file_certificate2" value="0">

                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_certificate3" data-modaltitle="Upload Certificate 3" data-accept="image,pdf" data-fileurl="<?= base_url() . 'uploads/certificate3/' . @$utilitys['data']['file_certificate3'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/certificate3/' . @$utilitys['data']['file_certificate3'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-address-card"></i> Certificate 3</button>
                        <input type="file" name="file_certificate3" hidden>
                        <input type="hidden" name="remove_file_certificate3" value="0">

                        <button type="button" class="btn btn-sm btn-outline-primary pr-2 btnOpenUploadModal" data-inputname="file_certificate4" data-modaltitle="Upload Certificate 4" data-accept="image,pdf" data-fileurl="<?= base_url() . 'uploads/certificate4/' . @$utilitys['data']['file_certificate4'] . '?v=' . time() ?>" data-filetype="<?= pathinfo(FCPATH . 'uploads/certificate4/' . @$utilitys['data']['file_certificate4'], PATHINFO_EXTENSION) ?>"><i class="fa-solid fa-address-card"></i> Certificate 4</button>
                        <input type="file" name="file_certificate4" hidden>
                        <input type="hidden" name="remove_file_certificate4" value="0">
                    </div>
                    <hr>
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-user-plus"></i> LEADS INFORMATION</span>
                    <hr>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-source_code">Leads Source <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" name="source_code" required>
                            <option value="">--SELECT--</option>
                            <?php if (!empty($utilitys['leads_sources']['data']['data'])) : ?>
                                <?php foreach ($utilitys['leads_sources']['data']['data'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    $source_name = '';
                                    if (@$utilitys['data']['source_code'] == $value['source_code']) {
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
                    <div class="form-group">
                        <textarea type="text" class="form-control bg-transparent" rows="2" name="source_information" value="<?= @$utilitys['data']['source_information'] ?>" <?= (($source_name !== 'OTHER') ? 'readonly' : ''); ?>><?= @$utilitys['data']['source_information'] ?></textarea>
                        <small class="text-danger pl-3" id="err-source_information" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-priority">Priority <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" name="priority" <?= ((@$utilitys['data']['status'] === 'YES') ? 'readonly' : '') ?> required>
                            <option value=""><span class="text-muted text-italic">Select an Option</span></option>
                            <?php if (!empty($utilitys['priority'])) : ?>
                                <?php foreach ($utilitys['priority'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['priority'] == $value) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-priority" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-follow_up_date">Follow up Date</label>
                        <input type="date" class="form-control bg-transparant" name="follow_up_date" value="<?= @$utilitys['data']['follow_up_date'] ?>" readonly required>
                        <small class="text-danger pl-3" id="err-follow_up_date" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-status">Status <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" name="status" <?= ((@$utilitys['data']['status'] === 'YES') ? 'readonly' : '') ?> required>
                            <option value=""><span class="text-muted text-light text-italic" style="color: #999;">Select an Option</span></option>
                            <?php if (!empty($utilitys['status'])) : ?>
                                <?php foreach ($utilitys['status'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['status'] == $value) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?= $value; ?>" <?= $selected ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger pl-3" id="err-status" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-note">Note</label>
                        <textarea type="text" class="form-control bg-transparant" name="note" value="<?= @$utilitys['data']['note'] ?>"><?= @$utilitys['data']['note'] ?></textarea>
                        <small class="text-danger pl-3" id="err-note" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-assigned_to">Assigned Counselor <label class="text-danger mb-0">*</label></label>
                        <select class="form-control bg-transparent" name="assigned_to" <?= ((user_group_check('GR_ADMIN', get_user()['id'])) ? '' : 'readonly'); ?> required>
                            <option value=""><span class="text-muted text-light text-italic">Select an Option</span></option>
                            <?php if (!empty($utilitys['assigned']['data']['data'])) : ?>
                                <?php foreach ($utilitys['assigned']['data']['data'] as $key => $value) : ?>
                                    <?php
                                    $selected = '';
                                    if (@$utilitys['data']['assigned_to'] == $value['user_id']) {
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
                    <span class="text-lg font-weight-bold"><i class="fa-solid fa-wallet"></i> PAYMENT INFORMATION</span>
                    <hr>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-invoice_number">Invoice No.</label>
                        <input type="text" class="form-control bg-transparant" name="invoice_number" value="<?= @$utilitys['data_invoice']['invoice_number'] ?>" readonly required>
                        <small class="text-danger pl-3" id="err-invoice_number" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-total_amount">Amount</label>
                        <input type="number" class="form-control bg-transparant" name="total_amount" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data_payment']['total_amount'] ?>" readonly>
                        <small class="text-danger pl-3" id="err-total_amount" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-discount_percent">Discount</label>
                        <input type="number" class="form-control bg-transparant" name="discount_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['discount_percent'] ?>" readonly>
                        <small class="text-danger pl-3" id="err-discount_percent" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-tax_percent">TAX</label>
                        <input type="number" class="form-control bg-transparant" name="tax_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['tax_percent'] ?>" <?= ((!empty($utilitys['data']['first_name'])) ? 'readonly' : '') ?>>
                        <small class="text-danger pl-3" id="err-tax_percent" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-final_amount">Final Payment</label>
                        <input type="number" class="form-control bg-transparant" name="final_amount" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data_payment']['final_amount'] ?>" readonly>
                        <small class="text-danger pl-3" id="err-final_amount" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-advance_amount">Advance</label>
                        <div class=" col-12 row pl-0 pr-0 mr-0">
                            <div class="col-8 pr-0 mr-0">
                                <input type="number" class="form-control bg-transparant" name="advance_amount" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data_invoice']['advance_amount'] ?>" readonly>
                            </div>
                            <div class="col-4 pr-0 mr-0">
                                <input type="number" class="form-control bg-transparant" name="advance_percent" inputmode="decimal" step="0.01" min="0" value="<?= @$utilitys['data_payment']['advance_percent'] ?>" <?= ((!empty($utilitys['data']['first_name'])) ? 'readonly' : '') ?>>
                            </div>
                        </div>
                        <small class="text-danger pl-3" id="err-advance_amount" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-remaining_balance">Remaining Balance</label>
                        <input type="number" class="form-control bg-transparant" name="remaining_balance" placeholder="Enter amount (INR)" inputmode="decimal" step="0.01" min="0" title="Enter amount in Indian Rupees (numbers only, up to 2 decimals)" value="<?= @$utilitys['data_payment']['remaining_balance'] ?>" readonly>
                        <small class="text-danger pl-3" id="err-remaining_balance" style="display: none;"></small>
                    </div>
                    <div class="form-group">
                        <label class="mb-0" id="lbl-due_date">Due Date</label>
                        <input type="date" class="form-control bg-transparant" name="due_date" value="<?= @$utilitys['data_payment']['due_date'] ?>" <?= ((!empty($utilitys['data']['first_name'])) ? 'readonly' : '') ?>>
                        <small class="text-danger pl-3" id="err-due_date" style="display: none;"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
</form>