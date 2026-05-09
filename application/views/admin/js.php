<script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(window).on('load', function() {
        $.loader('hide')
    });

    var jsURI = <?= json_encode($this->uri->rsegment_array()) ?>;
</script>

<?php
$filename = './apps.config.php';
if (file_exists($filename)) {
    require($filename);
}
?>

<script type="text/javascript" src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/bootstrap/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/overlayscrollbars/jquery.overlayScrollbars.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/theme/AdminLTE-3.2.0/adminlte.js?v=3.2.0"></script>

<!-- ----------- Load other plugins ----------- -->
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/intl-tel-input/intlTelInput.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/intl-tel-input/custom-script.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/modules/leads/js/leads_followup.js"></script>
<!-- ------------------------------------------ -->

<!-- <script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/uri.invy.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/alert.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/delete.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/restore.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/app.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/global-function.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/table.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/form-validation.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/form.invy.js"></script>

<?php
$filename = './application/modules/' . $this->uri->rsegments[1] . '/jsload.php';

if (file_exists($filename)) {
    require($filename);
}
?>