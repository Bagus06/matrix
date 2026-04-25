<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_model
{
    protected $module_name = 'test';
    protected $error_prefix = 'EXMP';
    protected $tb1 = 'test_table';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users/users_model');
    }

    public function remember_token($id = null, $token = null)
    {
        $output = [
            'status' => FALSE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => ''
            ],
            'data' => null

        ];

        if (!empty($id) && !empty($token)) {
            $data_post_user = [
                '_token' => $token
            ];
            $output = $this->users_model->users($id, $data_post_user, 'PATCH');
        } else {
            $output['debug'] = [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => 'ID or Token is empty.'
            ];
        }

        return $output;
    }
}
