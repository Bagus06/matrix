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
    case 'create':
    case 'edit':
        echo '<script src="' . $base_directory . 'upload.js"></script>';
        echo '<script src="' . $base_directory . 'geolocation.js"></script>';
        break;
    case 'detailed_info':
        echo '<script src="' . $base_directory . 'detailed_information.js"></script>';
        echo '<script src="' . $base_directory . 'report.js"></script>';
        break;
};
