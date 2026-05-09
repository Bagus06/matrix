<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'edit':
        echo '<link rel="stylesheet" href="' . $base_directory . 'upload.css">';
        echo '<link rel="stylesheet" href="' . $base_directory . 'edit.css">';
        echo '<link rel="stylesheet" href="' . $base_directory . 'table_detailed_student_info.css">';
        break;
};
