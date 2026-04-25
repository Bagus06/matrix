<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive p-0">
                    <table id="tb-<?= $this->uri->rsegments[1]; ?>" class="table table-sm table-striped table-hover">
                        <thead>
                            <tr class="th-src">
                                <th></th>
                                <th col-type="">university_name</th>
                                <th col-type="">short_name</th>
                                <th col-type="">university_type</th>
                                <th col-type="">ugc_code</th>
                                <th col-type="">aicte_code</th>
                                <th col-type="">naac_grade</th>
                                <th col-type="">contact</th>
                                <th col-type="">email</th>
                                <th col-type="">website</th>
                                <th col-type="">country</th>
                                <th col-type="">state</th>
                                <th col-type="">city</th>
                                <th col-type="">district</th>
                                <th col-type="">address</th>
                                <th col-type="">postal_code</th>
                                <th col-type="">status</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>University</th>
                                <th>short_name</th>
                                <th>university_type</th>
                                <th>ugc_code</th>
                                <th>aicte_code</th>
                                <th>naac_grade</th>
                                <th>contact</th>
                                <th>email</th>
                                <th>website</th>
                                <th>country</th>
                                <th>state</th>
                                <th>city</th>
                                <th>district</th>
                                <th>address</th>
                                <th>postal_code</th>
                                <th>status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>