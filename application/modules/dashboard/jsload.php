<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<script src="' . $base_directory . '{js file name}';
        break;
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        break;
};
