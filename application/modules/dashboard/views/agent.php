<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-12 row">
    <div class="col-12 col-md-12 col-xl-8">
        <div class="card">
            <div class="card-body">
                <form action="">
                    <table class="table table-borderless">
                        <tr>
                            <td colspan="2">
                                <h4><strong>Basic Info</strong></h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-enquiry_number">Enquiry No. <label class="text-danger">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-transparant" name="enquiry_number" value="<?= @$utilitys['data']['enquiry_number'] ?>" readonly required>
                                    <small class="text-danger pl-3" id="err-enquiry_number" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-first_name">First Name <label class="text-danger">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-transparant" name="first_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['first_name'] ?>" required>
                                    <small class="text-danger pl-3" id="err-first_name" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-last_name">Last Name</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-transparant" name="last_name" minlength="0" maxlength="50" value="<?= @$utilitys['data']['last_name'] ?>">
                                    <small class="text-danger pl-3" id="err-last_name" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-phone">Phone <label class="text-danger">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="tel" class="form-control bg-transparent" name="phone" placeholder="91xxxxxxxxxx" minlength="10" maxlength="13" title="Enter a valid Indian phone number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['phone'] ?>" required>
                                    <small class="text-danger pl-3" id="err-phone" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-whatsapp_number">WhatsApp</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="tel" class="form-control bg-transparent" name="whatsapp_number" placeholder="91xxxxxxxxxx" minlength="10" maxlength="13" title="Enter a valid Indian WhatsApp number (e.g., 919876543210)" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['whatsapp_number'] ?>">
                                    <small class="text-danger pl-3" id="err-whatsapp_number" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-email">Email <label class="text-danger">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="email" class="form-control bg-transparent" placeholder="yourname@example.com" minlength="6" maxlength="100" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>" required>
                                    <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Upload</strong>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC1"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 1</button>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC2"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 2</button>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC3"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 3</button>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadC4"><i class="fa-solid fa-cloud-arrow-up"></i> Certificate 4</button>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadAadhaar"><i class="fa-solid fa-address-card"></i> Aadhaar</button>
                                <button type="button" class="btn btn-sm btn-outline-primary pr-2" id="btnUploadPhoto"><i class="fa-solid fa-image-portrait"></i> Photo</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3" colspan="2">
                                <h4><strong>Advance Info</strong></h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-date_of_birth">Date of Birth</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="date" class="form-control bg-transparent" placeholder="YYYY-MM-DD" inputmode="numeric" pattern="^\d{4}-\d{2}-\d{2}$" autocomplete="bday" name="date_of_birth" value="<?= @$utilitys['data']['date_of_birth'] ?>">
                                    <small class="text-danger pl-3" id="err-date_of_birth" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-aadhaar_no">Aadhaar No.</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" class="form-control bg-transparent" inputmode="numeric" pattern="\d{12}" minlength="12" maxlength="12" autocomplete="off" name="aadhaar_no" value="<?= @$utilitys['data']['aadhaar_no'] ?>">
                                    <small class="text-danger pl-3" id="err-aadhaar_no" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-gender">Gender</label>
                            </td>
                            <td>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="gender-male" name="gender">
                                        <label for="gender-male">
                                            Male
                                        </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="gender-female" name="gender">
                                        <label for="gender-female">
                                            Female
                                        </label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="gender-other" name="gender" checked>
                                        <label for="gender-other">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-country_id">Country</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" title="Select country" name="country_id">
                                        <option value="<?= @$utilitys['data']['country_id']; ?>" selected="selected"><?= @$utilitys['data']['country']; ?></option>
                                    </select>
                                    <small class="text-danger pl-3" id="err-country_id" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-state_id">State</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" title="Select state" name="state_id">
                                        <option value="<?= @$utilitys['data']['state_id']; ?>" selected="selected"><?= @$utilitys['data']['state']; ?></option>
                                    </select>
                                    <small class="text-danger pl-3" id="err-state_id" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-city_id">Regency / City</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" title="Select state" name="city_id">
                                        <option value="<?= @$utilitys['data']['city_id']; ?>" selected="selected"><?= @$utilitys['data']['city']; ?></option>
                                    </select>
                                    <small class="text-danger pl-3" id="err-city_id" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-district_id">Subdistrict</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" title="Select state" name="district_id">
                                        <option value="<?= @$utilitys['data']['district_id']; ?>" selected="selected"><?= @$utilitys['data']['district']; ?></option>
                                    </select>
                                    <small class="text-danger pl-3" id="err-district_id" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-address">Address</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea type="text" class="form-control bg-transparent" rows="4" name="address" value="<?= @$utilitys['data']['address'] ?>"><?= @$utilitys['data']['address'] ?></textarea>
                                    <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-postal_code">Postal Code</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" class="form-control bg-transparent" pattern="^[0-9]{4,6}$" inputmode="numeric" minlength="4" maxlength="6" placeholder="Postal Code" name="postal_code" value="<?= @$utilitys['data']['postal_code'] ?>">
                                    <small class="text-danger pl-3" id="err-postal_code" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3" colspan="2">
                                <h4><strong>Student Info</strong></h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-student_no">Student No.</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-transparent" name="student_no" value="<?= @$utilitys['data']['student_no']; ?>" required="true" readonly>
                                    <small class="text-danger pl-3" id="err-student_no" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-university_id">University</label>
                            </td>
                            <td>
                                <div class="form-group">
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-course_id">Course</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" title="Select course" name="course_id">
                                        <option value="">--SELECT--</option>
                                    </select>
                                    <small class="text-danger pl-3" id="err-course_id" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-fees">Fees</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-transparent" value="<?= @$utilitys['data']['fees']; ?>" readonly>
                                    <small class="text-danger pl-3" id="err-fees" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3" colspan="2">
                                <h4><strong>Lead Info</strong></h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-source_code">Leads Source <label class="text-danger mb-0">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" name="source_code" required>
                                        <option value="">--SELECT--</option>
                                        <?php if (!empty($utilitys['leads_sources']['data']['data'])) : ?>
                                            <?php foreach ($utilitys['leads_sources']['data']['data'] as $key => $value) : ?>
                                                <?php
                                                $selected = '';
                                                if (@$utilitys['data']['source_code'] == $value['source_code']) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $value['source_code']; ?>" <?= $selected ?>><?= $value['source_code'] . (($value['source_name'] === 'B2B') ? ' ( Company Name : ' . $value['b2b_company_name'] . ' )' : (($value['source_name'] === 'REFERANCE') ? ' ( Referance Name : ' . $value['ref_name'] . ' )' : '')) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-danger pl-3" id="err-source_code" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-priority">Priority <label class="text-danger mb-0">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" name="priority" required>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-follow_up_date">Follow up Date</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="date" class="form-control bg-transparant" name="follow_up_date" value="<?= @$utilitys['data']['follow_up_date'] ?>" readonly required>
                                    <small class="text-danger pl-3" id="err-follow_up_date" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-initial_note">Initial Note</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea type="text" class="form-control bg-transparent" rows="2" name="initial_note" value="<?= @$utilitys['data']['initial_note'] ?>"><?= @$utilitys['data']['initial_note'] ?></textarea>
                                    <small class="text-danger pl-3" id="err-initial_note" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-status">Status <label class="text-danger mb-0">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" name="status" required>
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="lbl-note">Note</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea type="text" class="form-control bg-transparant" name="note" value="<?= @$utilitys['data']['note'] ?>"><?= @$utilitys['data']['note'] ?></textarea>
                                    <small class="text-danger pl-3" id="err-note" style="display: none;"></small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="pb-2" id="lbl-assigned_to">Assigned Counselor <label class="text-danger mb-0">*</label></label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control bg-transparent" name="assigned_to" <?= ((!empty(@$utilitys['data']['assigned_to'])) ? 'readonly' : ''); ?> required>
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
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>