<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        break;
    case 'edit':
    case 'create':
        echo '<script src="' . base_url() . 'assets/plugins/jsTree/jstree.min.js"></script>';
        echo '<script src="' . $base_directory . 'feature.js"></script>';
        // echo '<script src="' . $base_directory . 'menu-setting.js"></script>';
        break;
};
