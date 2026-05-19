<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<link rel="stylesheet" href="' . $base_directory . '{css file name}">';
        break;
    case 'edit':
    case 'create':
        echo '<link rel="stylesheet" href="' . $base_directory . 'upload.css">';
        break;
    case 'detailed_info':
        echo '<link rel="stylesheet" href="' . $base_directory . 'detailed_information.css">';
        break;
};
