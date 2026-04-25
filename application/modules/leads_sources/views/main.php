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
                                <th col-type="">source_code</th>
                                <th col-type="">source_name</th>
                                <th col-type=""></th>
                                <th col-type="">account</th>
                                <th col-type=""></th>
                                <th col-type="">b2b_company_name</th>
                                <th col-type="">ref_name</th>
                                <th col-type="">address</th>
                                <th col-type="">discount</th>
                                <th col-type="">phone</th>
                                <th col-type="">email</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>Code</th>
                                <th>Source</th>
                                <th>URL</th>
                                <th>Account</th>
                                <th>Password</th>
                                <th>Company Name</th>
                                <th>Referance Name</th>
                                <th>Address</th>
                                <th>Discount</th>
                                <th>Phone</th>
                                <th>Email</th>
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