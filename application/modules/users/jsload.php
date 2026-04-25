<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'main.js"></script>';
        break;
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="' . $base_directory . 'main.js"></script>';
        break;
    case 'edit':
        echo '<script src="' . $base_directory . 'edit.js"></script>';
    case 'create':
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        echo '<script src="' . $base_directory . 'geolocation.js"></script>';
        break;
};
