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
                                <th col-type="">enquiry_number</th>
                                <th col-type="">full_name</th>
                                <th col-type="">phone</th>
                                <th col-type="">email</th>
                                <th col-type="">university_name</th>
                                <th col-type="">course_name</th>
                                <th col-type="">assigned_to_name</th>
                                <th col-type="datetimerange">follow_up_date</th>
                                <th col-type="">source_code</th>
                                <th col-type="">status</th>
                                <th col-type="">note</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>Enquiry Number</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>University</th>
                                <th>Course</th>
                                <th>Assigned</th>
                                <th>Follow-Up Date</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Note</th>
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