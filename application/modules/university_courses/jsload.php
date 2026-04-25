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
    case 'edit':
    case 'create':
        echo '<script src="' . $base_directory . 'create_edit.js"></script>';
        break;
};
