<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card border-0 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="bg-primary bg-gradient text-white p-4">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="d-flex flex-column h-100 justify-content-center">
                    <div class="mb-2">
                        <span class="badge bg-light text-primary px-3 py-2">
                            <?= @$utilitys['data']['university_type'] ?: 'University' ?>
                        </span>

                        <?php if (!empty($utilitys['data']['naac_grade'])) : ?>
                            <span class="badge bg-warning text-dark px-3 py-2">
                                NAAC Grade : <?= @$utilitys['data']['naac_grade'] ?>
                            </span>
                        <?php endif; ?>

                        <?php if (@$utilitys['data']['status'] == 'ACTIVE') : ?>
                            <span class="badge bg-success px-3 py-2">
                                Active
                            </span>
                        <?php endif; ?>

                        <?php
                        $disabled_edit = 'disabled text-muted';
                        $edit_link = '';

                        # EDIT
                        if (permit_check('FT_UNI_EDT', get_user()['id'])) {
                            $disabled_edit = "";
                            $edit_link = base_url() . "universities/edit/" . encryptcst(@$utilitys['data']['id']);
                        }
                        ?>
                        <a href="<?= @$edit_link; ?>" class="btn btn-light btn-sm rounded-pill <?= $disabled_edit; ?> px-3">

                            <i class="fas fa-pen me-2"></i>
                            Edit University

                        </a>

                    </div>

                    <h2 class="fw-bold mb-2 university-title">
                        <?= @$utilitys['data']['university_name'] ?>
                        <?php if (!empty($utilitys['data']['short_name'])) : ?>
                            <span class="fw-light">
                                (<?= @$utilitys['data']['short_name'] ?>)
                            </span>
                        <?php endif; ?>
                    </h2>

                    <?php if (!empty(@$utilitys['data']['city'])): ?>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?= @$utilitys['data']['city'] ?>,
                            <?= @$utilitys['data']['state'] ?>,
                            <?= @$utilitys['data']['country'] ?>
                        </p>
                    <?php endif; ?>

                </div>
            </div>

            <div class="col-lg-3 text-center mt-4 mt-lg-0">
                <div class="rounded-4 p-3 shadow-sm">
                    <?php
                    $logo = FCPATH . 'uploads/universities_logo/' .  @$utilitys['data']['logo'];

                    if (is_file($logo)) {
                        $logo = base_url() . '/uploads/universities_logo/' .  @$utilitys['data']['logo'];
                    } else {
                        $logo = 'https://dummyimage.com/400/bababa/a6a6a6';
                    }
                    ?>
                    <img src="<?= $logo; ?>"
                        class="img-fluid rounded"
                        style="max-height: 140px; object-fit: contain;"
                        alt="University Logo">
                </div>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">

        <div class="row">

            <!-- Left Content -->
            <div class="col-lg-8">

                <!-- About -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fas fa-university me-2"></i>
                        About University
                    </h5>

                    <div class="bg-light rounded-4 p-4 border">
                        <p class="mb-0 text-muted" style="line-height: 1.8;">
                            <?= !empty($utilitys['data']['description'])
                                ? nl2br($utilitys['data']['description'])
                                : 'No description available.' ?>
                        </p>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Address Information
                    </h5>

                    <div class="card border-0 bg-light">
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Country</small>
                                    <strong><?= @$utilitys['data']['country'] ?: '-' ?></strong>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">State</small>
                                    <strong><?= @$utilitys['data']['state'] ?: '-' ?></strong>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">City</small>
                                    <strong><?= @$utilitys['data']['city'] ?: '-' ?></strong>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">District</small>
                                    <strong><?= @$utilitys['data']['district'] ?: '-' ?></strong>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <small class="text-muted d-block">Full Address</small>
                                    <strong><?= @$utilitys['data']['address'] ?: '-' ?></strong>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted d-block">Postal Code</small>
                                    <strong><?= @$utilitys['data']['postal_code'] ?: '-' ?></strong>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Note -->
                <?php if (!empty($utilitys['data']['note'])) : ?>
                    <div class="alert alert-warning border-0 rounded-4">
                        <h6 class="fw-bold">
                            <i class="fas fa-sticky-note me-2"></i>
                            <strong>Note</strong>
                        </h6>

                        <div class="mb-0">
                            <?= nl2br($utilitys['data']['note']) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">

                <!-- Quick Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold text-primary mb-0">
                            Quick Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">UGC Code</span>
                            <strong><?= @$utilitys['data']['ugc_code'] ?: '-' ?></strong>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">AICTE Code</span>
                            <strong><?= @$utilitys['data']['aicte_code'] ?: '-' ?></strong>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">NAAC Grade</span>
                            <span class="badge bg-success">
                                <?= @$utilitys['data']['naac_grade'] ?: '-' ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Status</span>

                            <?php if (@$utilitys['data']['status'] == 'ACTIVE') : ?>
                                <span class="badge bg-success">ACTIVE</span>
                            <?php else : ?>
                                <span class="badge bg-danger">
                                    <?= @$utilitys['data']['status'] ?: 'INACTIVE' ?>
                                </span>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <!-- Contact -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold text-primary mb-0">
                            Contact Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                Phone Number
                            </small>

                            <a href="tel:<?= @$utilitys['data']['contact'] ?>"
                                class="text-decoration-none fw-semibold">
                                <?= @$utilitys['data']['contact'] ?: '-' ?>
                            </a>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                Email Address
                            </small>

                            <a href="mailto:<?= @$utilitys['data']['email'] ?>"
                                class="text-decoration-none fw-semibold">
                                <?= @$utilitys['data']['email'] ?: '-' ?>
                            </a>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">
                                Website
                            </small>

                            <?php if (!empty($utilitys['data']['website'])) : ?>
                                <a href="<?= @$utilitys['data']['website'] ?>"
                                    target="_blank"
                                    class="text-decoration-none fw-semibold">
                                    <?= @$utilitys['data']['website'] ?>
                                </a>
                            <?php else : ?>
                                <span>-</span>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
<!-- ========================================= -->
<!-- UNIVERSITY COURSES LIST -->
<!-- ========================================= -->

<div class="card border-0 shadow-sm mt-4">

    <!-- Header -->
    <div class="card-header bg-white border-0 p-4">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">

            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-graduation-cap me-2"></i>
                    University Courses
                </h4>

                <p class="text-muted mb-0">
                    Available programs and course information
                </p>
            </div>

            <div class="mt-3 mt-lg-0">
                <span class="badge bg-primary px-3 py-2 fs-6">
                    <?= @$utilitys['courses']['data']['filtered_record'] ?> Courses
                </span>

                <a href="<?= @$internal['university_courses_create_url']; ?>" title="<?= ((empty(@$internal['university_courses_create_title'])) ? 'Create' : @$internal['university_courses_create_title']); ?>" class="btn btn-primary btn-sm rounded-pill btn-create <?= ((empty(@$internal['university_courses_create_url'])) ? 'disabled' : ''); ?>" data-modalid="<?= @$internal['university_courses_create_modal']; ?>" data-formname="<?= @$internal['university_courses_create_form']; ?>" data-formtype="<?= @$internal['university_courses_create_formtype']; ?>" <?= ((empty(@$internal['university_courses_create_url'])) ? 'disabled' : ''); ?>>

                    <i class="fas fa-circle-plus"></i>
                    Add Course

                </a>
            </div>

        </div>

    </div>

    <!-- Search & Filter -->
    <div class="card-body border-top border-bottom bg-light">
        <form action="" method="post">

            <div class="row">

                <div class="col-md-4 mb-3 mb-md-0">
                    <input type="text"
                        name="course_name"
                        class="form-control"
                        placeholder="Search course name..."
                        value="<?= @$utilitys['search']['course_name']; ?>">
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <select class="form-control" name="course_level">
                        <option value="">All Levels</option>
                        <?php foreach ($utilitys['course_level'] as $key => $value): ?>
                            <?php
                            $selected = '';
                            if (@$value == @$utilitys['search']['course_level']) {
                                $selected = 'selected';
                            }
                            ?>
                            <option <?= $selected; ?>><?= $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="course_type">
                        <option value="">All Types</option>
                        <?php foreach ($utilitys['course_type'] as $key => $value): ?>
                            <?php
                            $selected = '';
                            if (@$value == @$utilitys['search']['course_type']) {
                                $selected = 'selected';
                            }
                            ?>
                            <option <?= $selected; ?>><?= $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-link"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>

            </div>

        </form>

    </div>

    <!-- Course List -->
    <div class="card-body p-0">

        <?php if (!empty($utilitys['courses']['data']['data'])) : ?>

            <?php foreach ($utilitys['courses']['data']['data'] as $key => $value) : ?>

                <div class="p-4 border-bottom">

                    <div class="row align-items-center">

                        <!-- Left -->
                        <div class="col-lg-7 mb-4 mb-lg-0">

                            <!-- Top -->
                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <div class="d-flex flex-wrap align-items-center gap-2">

                                    <span class="badge bg-primary mr-1">
                                        <?= @$value['course_level'] ?>
                                    </span>

                                    <span class="badge bg-light text-dark border mr-1">
                                        <?= @$value['course_type'] ?>
                                    </span>

                                    <?php if (@$value['status'] == 'ACTIVE') : ?>
                                        <span class="badge bg-success mr-1">
                                            <?= @$value['status'] ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">
                                            <?= @$value['status'] ?>
                                        </span>
                                    <?php endif; ?>

                                </div>

                                <!-- Action Button -->
                                <div class="dropdown">

                                    <button class="btn btn-light btn-sm border rounded-circle p-0 d-flex align-items-center justify-content-center"
                                        type="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        style="width:32px; height:32px;">

                                        <i class="fas fa-ellipsis-v small"></i>

                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right shadow border-0">

                                        <?php
                                        // Declas variable of attributs edit button
                                        $disabled_delete = "disabled";
                                        $delete_link = '';

                                        // Declar variable of attributs edit button
                                        $disabled_edit = 'disabled text-muted';
                                        $edit_link = '';

                                        # EDIT
                                        if (permit_check('FT_UCS_EDT', get_user()['id'])) {
                                            $disabled_edit = "";
                                            $edit_link = base_url() . "university_courses/edit/" . encryptcst($value["id"]);
                                        }

                                        # SOFT DELETE
                                        if (permit_check('FT_UCS_DEL', get_user()['id'])) {
                                            $disabled_delete = "";
                                            $delete_link = encryptcst($value["id"]);
                                        }
                                        ?>
                                        <a title='Detail/Edit rows' href='<?= $edit_link; ?>' class='dropdown-item <?= $disabled_edit; ?>'>

                                            <i class="fas fa-pen mr-2 text-warning"></i>
                                            Edit Course

                                        </a>

                                        <button title='Delete item - <?= $value['course_name'] ?>' data-id='<?= $delete_link; ?>' data-module='university_courses' data-item='<?= $value['course_name'] ?>' class='btn btn-link btn-delete-external text-danger <?= $disabled_delete; ?>'>

                                            <i class="fas fa-trash-alt mr-2 text-danger"></i>
                                            Delete Course

                                        </button>

                                    </div>

                                </div>

                            </div>

                            <!-- Course Name -->
                            <h5 class="fw-bold mb-1">
                                <?= @$value['course_name'] ?>
                            </h5>

                            <!-- Course Code -->
                            <?php if (!empty($value['course_code'])) : ?>

                                <div class="text-muted small mb-3">
                                    Course Code :
                                    <strong>
                                        <?= @$value['course_code'] ?>
                                    </strong>
                                </div>

                            <?php endif; ?>

                            <!-- Description -->
                            <p class="text-muted mb-3"
                                style="line-height:1.7;">

                                <?= !empty($value['description'])
                                    ? word_limiter(strip_tags($value['description']), 25)
                                    : 'No description available for this course.' ?>

                            </p>

                            <!-- Extra Info -->
                            <div class="d-flex flex-wrap gap-3">

                                <div class="bg-light rounded px-3 py-2 small">
                                    <span class="text-muted">
                                        Duration :
                                    </span>

                                    <strong>
                                        <?= @$value['duration_year'] ?> Years
                                    </strong>
                                </div>

                                <div class="bg-light rounded px-3 py-2 small">
                                    <span class="text-muted">
                                        Semester :
                                    </span>

                                    <strong>
                                        <?= @$value['total_semesters'] ?>
                                    </strong>
                                </div>

                                <?php if (!empty($value['last_updated_fees'])) : ?>

                                    <div class="bg-light rounded px-3 py-2 small">
                                        <span class="text-muted">
                                            Updated Fee :
                                        </span>

                                        <strong>
                                            <?= date('d M Y', strtotime($value['last_updated_fees'])) ?>
                                        </strong>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- Right -->
                        <div class="col-lg-5">

                            <div class="bg-light rounded-4 p-4 h-100">

                                <!-- Fee -->
                                <div class="mb-3">

                                    <small class="text-muted d-block mb-1">
                                        Total Course Fee
                                    </small>

                                    <?php if (
                                        !empty($value['discount_percent']) &&
                                        @$value['discount_percent'] > 0
                                    ) : ?>

                                        <div class="mb-1">

                                            <span class="text-decoration-line-through text-muted">
                                                <?= INR(($value['final_fee'] - ($value['final_fee'] * ((float) $value['discount_percent'] / 100)))); ?>
                                            </span>

                                            <span class="badge bg-danger ms-2">
                                                <?= @$value['discount_percent'] ?>% OFF
                                            </span>

                                        </div>

                                    <?php endif; ?>

                                    <h3 class="fw-bold text-primary mb-0">

                                        <?= INR($value['final_fee']); ?>

                                    </h3>

                                </div>

                                <!-- Discount Duration -->
                                <?php if (
                                    !empty($value['discount_duration_start']) &&
                                    !empty($value['discount_duration_end'])
                                ) : ?>

                                    <div class="alert alert-success py-2 px-3 small mb-3">

                                        <i class="fas fa-tags me-2"></i>

                                        Offer valid from
                                        <strong>
                                            <?= date('d M Y', strtotime($value['discount_duration_start'])) ?>
                                        </strong>
                                        to
                                        <strong>
                                            <?= date('d M Y', strtotime($value['discount_duration_end'])) ?>
                                        </strong>

                                    </div>

                                <?php endif; ?>

                                <!-- Eligibility -->
                                <?php if (!empty($value['eligibility'])) : ?>

                                    <div class="mb-3">

                                        <small class="text-muted d-block mb-2">
                                            Eligibility
                                        </small>

                                        <div class="small border rounded bg-white p-3">
                                            <?= nl2br($value['eligibility']) ?>
                                        </div>

                                    </div>

                                <?php endif; ?>

                                <!-- Action -->
                                <!-- <div class="d-grid">

                                    <button class="btn btn-primary rounded-3">

                                        <i class="fas fa-eye me-2"></i>
                                        View Course Details

                                    </button>

                                </div> -->

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <!-- Empty State -->
            <div class="text-center py-5">

                <h5 class="fw-bold">
                    No Courses Available
                </h5>

                <p class="text-muted mb-0">
                    There are currently no courses available for this university.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>