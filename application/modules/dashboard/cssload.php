<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<link rel="stylesheet" href="' . $base_directory . '{css file name}">';
        echo '<link rel="stylesheet" href="' . $base_directory . 'style.css">';
        break;
    case 'agent':
        echo '<link rel="stylesheet" href="' . base_url() . 'assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">';
        echo '<link rel="stylesheet" href="' . $base_directory . 'agent-style.css">';
        break;
};
