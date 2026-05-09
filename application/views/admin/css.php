<link rel="manifest" href="<?= base_url() ?>manifest.json">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="<?= base_url() ?>assets/theme/AdminLTE-3.2.0/adminlte.min.css?v=3.2.0">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/overlayscrollbars/OverlayScrollbars.min.css">

<!-- ----------- Load other plugins ----------- -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweetalert2/bootstrap-4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/invy/col-table-style.invy.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/intl-tel-input/intlTelInput.css">
<style>
    .iti__flag {
        background-image: url("<?= base_url('assets/plugins/intl-tel-input/img/flags.png'); ?>");
    }

    @media (min-resolution: 2x) {
        .iti__flag {
            background-image: url("<?= base_url('assets/plugins/intl-tel-input/img/flags@2x.png'); ?>");
        }
    }

    .iti {
        display: block !important;
    }
</style>
<!-- ------------------------------------------ -->

<link rel="stylesheet" href="<?= base_url() ?>assets/theme/AdminLTE-3.2.0/custom.style.css">

<?php
$filename = './application/modules/' . $this->uri->rsegments[1] . '/cssload.php';

if (file_exists($filename)) {
    require($filename);
}
?>