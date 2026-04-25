<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        echo '<link rel="stylesheet" href="' . base_url() . 'assets/plugins/jsTree/style.min.css">';
        break;
};
