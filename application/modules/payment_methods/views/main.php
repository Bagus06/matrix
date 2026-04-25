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
                                <th col-type="">method_code</th>
                                <th col-type="">methods_name</th>
                                <th col-type="">category</th>
                                <th col-type="">account_name</th>
                                <th col-type="">account_identifier</th>
                                <th col-type="">bank_name</th>
                                <th col-type="">branch_name</th>
                                <th col-type="">ifsc_code</th>
                                <th col-type="">status</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>Code</th>
                                <th>Method</th>
                                <th>Category</th>
                                <th>Account Name</th>
                                <th>Account Identifier</th>
                                <th>Bank</th>
                                <th>Branch</th>
                                <th>IFSC</th>
                                <th>Status</th>
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