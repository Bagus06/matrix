<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_user')) {
    function get_user()
    {
        $output = false;
        $CI = &get_instance();
        $link = str_replace('/', '_', base_url() . '_logged_in');
        $session = $CI->session->userdata($link);

        if (!empty($session)) {
            $session['id'] = decryptcst($session['id']);

            $CI->load->model('users/users_model');
            $user_detailed = $CI->users_model->detailed($session['id']);

            $output = $session;

            if (!empty($user_detailed['data'])) {
                unset($user_detailed['data']['id']);

                $output = array_merge($session, $user_detailed['data']);
            }
        }

        return $output;
    }
}

if (!function_exists('user_group_check')) {
    function user_group_check($group_code = null, $user_id = null)
    {
        $output = FALSE;
        $CI = &get_instance();
        $CI->load->model('users/users_model');

        $user_detailed = $CI->users_model->detailed($user_id);
        if (in_array($group_code, json_decode(@$user_detailed['data']['permission_group'], true))) {
            $output = TRUE;
        }

        return $output;
    }
}

if (!function_exists('user_activity_logs')) {
    function user_activity_logs($id, $username, $email, $success, $message)
    {
        $output = FALSE;
        $CI = &get_instance();
        $CI->load->library('user_agent');

        if (!empty($id)) {
            $ip = trim(file_get_contents("http://ifconfig.me/ip"));
            $user_agent = $CI->agent->agent_string();
            $location = file_get_contents("http://ip-api.com/json/{$ip}?fields=66846719");
            (empty($success)) ? $success = 0 : '';

            $query = "INSERT INTO user_activity_logs (user_id, username, email, ip_address, location, user_agent, success, message) VALUES ($id, '$username', '$email', '$ip', " . $CI->db->escape($location) . ", '$user_agent', $success, '$message')";
            $excute_query = $CI->db->query($query);

            if (empty($CI->db->error()['code'])) {
                $output = TRUE;
            }
        }

        return $output;
    }
}
