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
                                <th col-type="">geo_villages.id</th>
                                <th col-type="">geo_contries.name</th>
                                <th col-type="">geo_states.name</th>
                                <th col-type="">geo_cities.name</th>
                                <th col-type="">geo_districts.name</th>
                                <th col-type="">geo_villages.name</th>
                                <td col-type="" style="text-align:right"><?= (user_ag() == "mobile") ? "<button type='button' id='btn-col-search' class='btn btn-link'><i class='fa-solid fa-magnifying-glass'></i></button>" : "" ?></td>
                            </tr>
                            <tr id="th" data-thd="1">
                                <th></th>
                                <th>ID</th>
                                <th>Country</th>
                                <th>State / Union Territory</th>
                                <th>District</th>
                                <th>Sub-district</th>
                                <th>Village</th>
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