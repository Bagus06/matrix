<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';
switch ($this->uri->rsegments[2]) {
    case 'main':
        echo '<script src="' . $base_directory . 'main.js"></script>';
        break;
    case 'setup':
        echo '<script src="' . $base_directory . 'setup.js"></script>';
        break;
}
