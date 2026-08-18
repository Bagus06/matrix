<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        echo '<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>';
        echo '<script src="' . $base_directory . 'default_script.js"></script>';
        break;
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        break;
};
