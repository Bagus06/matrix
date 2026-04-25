<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_model
{
    protected $module_name = 'dashboard';
    protected $error_prefix = 'DASH';

    public function __construct()
    {
        parent::__construct();
    }
}
