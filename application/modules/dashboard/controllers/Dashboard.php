<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Dashboard extends CI_Controller
{
    protected $module = 'dashboard';

    public function __construct()
    {
        parent::__construct();
        check_auth();

        $this->load->model('dashboard_model');
    }

    public function main()
    {
        if (user_group_check('GR_MKTST', get_user()['id']) && !user_group_check('GR_ADMIN', get_user()['id'])) {
            redirect(base_url() . 'leads/create');
        }

        $this->load->view('index');
    }

    public function agent()
    {
        $this->load->view('index');
    }
}
