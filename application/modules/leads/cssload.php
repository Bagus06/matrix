<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/css/';

switch ($this->uri->rsegments[2]) {
    case 'create':
    case 'edit':
        echo '<link rel="stylesheet" href="' . $base_directory . 'table_create.css">';
        break;
};
