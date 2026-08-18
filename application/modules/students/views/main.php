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
                                <th col-type="">students.student_number</th>
                                <th col-type="">students.full_name</th>
                                <th col-type="">students.phone</th>
                                <th col-type="">students.email</th>
                                <th col-type="">university_name</th>
                                <th col-type="">course_name</th>
                                <th col-type="">CONCAT(students.country, ' ', students.state, ' ', students.city)</th>
                                <th col-type="">additional_certificate</th>
                                <th col-type="">assigned_to_name</th>
                                <th col-type="datetimerange">students.created_at</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>University</th>
                                <th>Course</th>
                                <th>Place</th>
                                <th>Additional certificate or attestation</th>
                                <th>Assigned Counselor</th>
                                <th>Document Submit Date</th>
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