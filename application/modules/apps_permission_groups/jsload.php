<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        break;
    case 'create':
    case 'edit':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'edit.js"></script>';
        break;
};
