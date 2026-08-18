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
        if (!$this->can_view_admin_dashboard()) {
            redirect(base_url() . 'leads/create');
        }

        $this->load->view('index');
    }

    public function overview()
    {
        if (!$this->can_view_admin_dashboard()) {
            return $this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(['status'=>false,'message'=>'Access denied.']));
        }
        return $this->output->set_content_type('application/json','utf-8')->set_output(json_encode(['status'=>true,'data'=>$this->dashboard_model->admin_overview()]));
    }

    private function can_view_admin_dashboard()
    {
        $user=get_user();
        return strtoupper($user['username'])==='DEVELOPER'||user_group_check('GR_ADMIN',$user['id']);
    }

    public function agent()
    {
        $this->load->view('index');
    }
}
