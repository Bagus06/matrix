<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Auth extends CI_Controller
{
    protected $module = 'auth';
    protected $error_prefix = 'AUTH';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('users/users_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->example_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function login()
    {
        $utilitys = null;
        $alert = null;

        $input_post = @$this->input->post();
        $get_token = get_cookie('remember_token');

        $wc_query = '';
        if (!empty($input_post)) {
            $user = $this->db->escape(@$input_post['user']);
            $wc_query = "users.username = $user OR users.email = $user";
        } elseif (!empty($get_token)) {
            $token = $this->db->escape(@$get_token);
            $wc_query = "users._token = $token";
        }
        $user = $this->users_model->detailed('', $wc_query);

        if (!empty($user['data'])) {
            $login_status = FALSE;
            $login_status_message = 'Wrong password';

            if (decrypt(@$input_post['password'], $user['data']['password']) || !empty($get_token)) {
                $login_status = TRUE;
            }

            if ($login_status) {
                $login_status_message = 'Login success';
                if ($user['data']['verification']) {
                    if ($user['data']['account_status'] === 'on') {
                        $session_data = [
                            'id' => encryptcst($user['data']['user_id']),
                            'username' => $user['data']['username'],
                            'email' => $user['data']['email'],
                        ];
                        $this->session->set_userdata(str_replace('/', '_', base_url() . '_logged_in'), $session_data);

                        if (@$input_post['remember'] === 'on') {
                            $token = bin2hex(random_bytes(30));
                            $update_token = $this->auth_model->remember_token($user['data']['user_id'], $token);

                            if ($update_token['status']) {
                                set_cookie('remember_token', $token, 60 * 60 * 24 * 30);
                            }
                        }

                        user_activity_logs(
                            $user['data']['user_id'],
                            $user['data']['username'],
                            $user['data']['email'],
                            $login_status,
                            $login_status_message
                        );
                        redirect(base_url());
                    } else {
                        $login_status = FALSE;
                        $alert = get_error_info("$this->error_prefix-LGN-E004");
                        $login_status_message = @$alert['message'];
                    }
                } else {
                    $login_status = FALSE;
                    $alert = get_error_info("$this->error_prefix-LGN-E003");
                    $login_status_message = @$alert['message'];
                }
            } else {
                $login_status = FALSE;
                $alert = get_error_info("$this->error_prefix-LGN-E002");
                $login_status_message = @$alert['message'];
            }

            user_activity_logs(
                $user['data']['user_id'],
                $user['data']['username'],
                $user['data']['email'],
                $login_status,
                $login_status_message
            );
        } else {
            if (!empty($input_post)) {
                $login_status = FALSE;
                $alert = get_error_info("$this->error_prefix-LGN-E001");
                $login_status_message = @$alert['message'];
            }
        }

        $this->load->view('login', ['utilitys' => $utilitys, 'alert' => $alert]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        delete_cookie('remember_token');

        redirect(base_url() . 'login');
    }
}
