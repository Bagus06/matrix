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
                                <th col-type="">course_name</th>
                                <th col-type="">course_code</th>
                                <th col-type="">university_name</th>
                                <th col-type="">course_level</th>
                                <th col-type="">course_type</th>
                                <th col-type="">duration_year</th>
                                <th col-type="">total_semesters</th>
                                <th col-type="">eligibility</th>
                                <th col-type="">description</th>
                                <th col-type="">status</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th col-type="">Course</th>
                                <th col-type="">Course Code</th>
                                <th col-type="">University</th>
                                <th col-type="">Level</th>
                                <th col-type="">Type</th>
                                <th col-type="">Duration</th>
                                <th col-type="">Semesters</th>
                                <th col-type="">Eligibility</th>
                                <th col-type="">Description</th>
                                <th col-type="">Status</th>
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