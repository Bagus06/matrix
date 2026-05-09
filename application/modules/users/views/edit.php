<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form method="post" action="" enctype="multipart/form-data">
    <div class="card">
        <div class="card-body">
            <div class="col-12">
                <table class="table table-borderless table-sm" style="margin: 0px;">
                    <tr>
                        <td colspan="2">
                            <p class="lead">Upload a profile photo. After choosing an image you'll be able to reposition and crop it.</p>

                            <div class="avatar-row">
                                <input type="file" id="file-input" accept="image/*" hidden>
                                <div class="avatar-frame" id="avatar-preview" aria-hidden="true">
                                    <?php
                                    if (!empty($utilitys['data'])) {

                                        $profile = '.assets/img/profile/' .  @$utilitys['data']['photo'];

                                        if (file_exists($profile)) {
                                            $profile = @$utilitys['data']['photo'];
                                        } else {
                                            $profile = 'default_profile.png';
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

                                <div class="controls">
                                    <div class="mb16">
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-upload">Change Photo</button>
                                        <div class="small muted">Recommended: square image, at least 800×800. You can move and zoom to adjust crop.</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-12">
                <table class="table table-borderless table-sm" style="margin: 0px;">
                    <tr>
                        <td id="lbl-name">Full Name</td>
                        <td>
                            <input type="text" class="form-control form-control-borderless bg-transparent" style="text-transform:uppercase" minlength="5" maxlength="100" placeholder="Your Name" name="name" value="<?= @$utilitys['data']['name'] ?>" required>
                            <small class="text-danger pl-3" id="err-name" style="display: none;"></small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Account Info
        </div>
        <div class="card-body">
            <div class="col-12">
                <table class="table table-borderless table-sm" style="margin: 0px;">
                    <tr>
                        <td id="lbl-username">Username</td>
                        <td>
                            <input type="text" class="form-control bg-transparent" style="text-transform:uppercase" placeholder="Enter username (3–20 chars)" pattern="^(?!.*[._]{2})[a-zA-Z](?:[a-zA-Z0-9._]{1,18}[a-zA-Z0-9])$" minlength="3" maxlength="20" name="username" id="username" value="<?= @$utilitys['data']['username'] ?>" required <?= ((!empty($utilitys['data']['username'])) ? 'readonly' : '') ?>>
                            <small class="text-danger pl-3" id="err-username" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-email">Email</td>
                        <td>
                            <input type="email" class="form-control bg-transparent" style="text-transform:lowercase" placeholder="yourname@example.com" minlength="6" maxlength="64" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$" inputmode="email" autocomplete="email" name="email" value="<?= @$utilitys['data']['email'] ?>" required>
                            <small class="text-danger pl-3" id="err-email" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-phone">Phone / Mobile</td>
                        <td>
                            <input type="tel" class="form-control bg-transparent" name="phone" minlength="7" maxlength="15" inputmode="tel" autocomplete="tel" value="<?= @$utilitys['data']['phone'] ?>" required>
                            <small class="text-danger pl-3" id="err-phone" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-password">Password</td>
                        <td>
                            <input type="password" id="password" class="form-control bg-transparent" name="password" placeholder="********" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$" title="Must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number" value="" <?= ((empty(@$utilitys['data'])) ? 'required' : '') ?>>
                            <small class="text-danger pl-3" id="err-password" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-confirm_password">Confirm Password <?= ((empty(@$utilitys['data'])) ? '<label class="text-danger">*</label>' : '') ?></td>
                        <td>
                            <input type="password" id="confirm_password" class="form-control bg-transparent" name="confirm_password" placeholder="********" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$" title="Must contain at least 1 uppercase letter, 1 lowercase letter, and 1 number" value="" <?= ((empty(@$utilitys['data'])) ? 'required' : '') ?>>
                            <small class="text-danger pl-3" id="err-confirm_password" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-permission_group">Permission</td>
                        <td>
                            <select class="form-control form-control-border select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="permission_group[]">
                                <option value="">--Select--</option>
                                <?php if (!empty($utilitys['permission_group']['data']['data'])) : ?>
                                    <?php foreach ($utilitys['permission_group']['data']['data'] as $key => $value) : ?>
                                        <?php
                                        $selected = '';
                                        if (in_array($value['group_code'], @$utilitys['data']['permission_group'])) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?= $value['group_code'] ?>" <?= $selected; ?>><?= $value['group_code'] ?> (<?= $value['group_title'] ?>)</option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-danger pl-3" id="err-permission_group" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-account_status">Account Status</td>
                        <td class="text-danger">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input custom-control-input-default" name="account_status" id="account_status" <?= ((@$utilitys['data']['account_status']) ? 'checked' : '') ?>>
                                <label for="account_status" class="custom-control-label">User account status.</label>
                            </div>
                            <small class="text-danger pl-3" id="err-account_status" style="display: none;"></small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Profile Info
        </div>
        <div class="card-body">
            <div class="col-12">
                <table class="table table-borderless table-sm" style="margin: 0px;">
                    <tr>
                        <td id="lbl-date_of_birth">Date of Birth</td>
                        <td>
                            <input type="date" class="form-control form-control-borderless bg-transparent" placeholder="YYYY-MM-DD" inputmode="numeric" pattern="^\d{4}-\d{2}-\d{2}$" autocomplete="bday" name="date_of_birth" value="<?= @$utilitys['data']['date_of_birth'] ?>" required>
                            <small class="text-danger pl-3" id="err-date_of_birth" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-place_of_birth">Place of Birth</td>
                        <td>
                            <input type="text" class="form-control form-control-borderless bg-transparent" placeholder="Place of Birth" inputmode="text" autocomplete="address-level2" autocorrect="on" autocapitalize="words" spellcheck="false" minlength="2" maxlength="50" name="place_of_birth" value="<?= @$utilitys['data']['place_of_birth'] ?>" required>
                            <small class="text-danger pl-3" id="err-place_of_birth" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-country_id">Country</td>
                        <td>
                            <select class="form-control bg-transparent" title="Select country" name="country_id">
                                <option value="<?= @$utilitys['data']['country_id']; ?>" selected="selected"><?= @$utilitys['data']['country']; ?></option>
                            </select>
                            <small class="text-danger pl-3" id="err-country_id" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-state_id">State</td>
                        <td>
                            <select class="form-control bg-transparent" title="Select state" name="state_id">
                                <option value="<?= @$utilitys['data']['state_id']; ?>" selected="selected"><?= @$utilitys['data']['state']; ?></option>
                            </select>
                            <small class="text-danger pl-3" id="err-state_id" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-city_id">Regency / City</td>
                        <td>
                            <select class="form-control bg-transparent" title="Select state" name="city_id">
                                <option value="<?= @$utilitys['data']['city_id']; ?>" selected="selected"><?= @$utilitys['data']['city']; ?></option>
                            </select>
                            <small class="text-danger pl-3" id="err-city_id" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-district_id">Subdistrict</td>
                        <td>
                            <select class="form-control bg-transparent" title="Select state" name="district_id">
                                <option value="<?= @$utilitys['data']['district_id']; ?>" selected="selected"><?= @$utilitys['data']['district']; ?></option>
                            </select>
                            <small class="text-danger pl-3" id="err-district_id" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-village_id">Village</td>
                        <td>
                            <select class="form-control bg-transparent" title="Select state" name="village_id">
                                <option value="<?= @$utilitys['data']['village_id']; ?>" selected="selected"><?= @$utilitys['data']['village']; ?></option>
                            </select>
                            <small class="text-danger pl-3" id="err-village_id" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-address">Address</td>
                        <td>
                            <textarea type="text" class="form-control form-control-borderless bg-transparent" rows="3" pattern="^[A-Za-z0-9\s.,#\/\-]{5,100}$" minlength="5" maxlength="100" placeholder="House No. 12, Green Street" name="address" value="<?= @$utilitys['data']['address'] ?>"><?= @$utilitys['data']['address'] ?></textarea>
                            <small class="text-danger pl-3" id="err-address" style="display: none;"></small>
                        </td>
                    </tr>
                    <tr>
                        <td id="lbl-postal_code">Postal Code</td>
                        <td>
                            <input type="number" class="form-control form-control-borderless bg-transparent" pattern="^[0-9]{4,6}$" inputmode="numeric" minlength="4" maxlength="6" placeholder="Postal Code" name="postal_code" value="<?= @$utilitys['data']['postal_code'] ?>">
                            <small class="text-danger pl-3" id="err-postal_code" style="display: none;"></small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <button type="submit" id="<?= ((empty($internal['create_form'])) ? @$internal['edit_form'] : @$internal['create_form']) ?>" style="display: none;"></button>
</form>