<link rel="manifest" href="<?= base_url() ?>manifest.json">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="<?= base_url() ?>assets/theme/AdminLTE-3.2.0/adminlte.min.css?v=3.2.0">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/overlayscrollbars/OverlayScrollbars.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-6.4.2/css/all.min.css">

<!-- ----------- Load other plugins ----------- -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/sweetalert2/bootstrap-4.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/toastr/toastr.min.css">
<!-- ------------------------------------------ -->

<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'login':
        echo '<link rel="stylesheet" href="' . $base_directory . 'login.css">';
        break;
};
