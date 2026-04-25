<script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(window).on('load', function() {
        $.loader('hide')
    });
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
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- ------------------------------------------ -->

<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/uri.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/alert.invy.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/invy/global-function.invy.js"></script>

<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        break;
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        break;
};
?>