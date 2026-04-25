<?php defined('BASEPATH') or exit('No direct script access allowed');

if ($this->agent->is_mobile()) {
    $this->load->view("admin/index");
} else {
    $this->load->view("admin/index");
}
