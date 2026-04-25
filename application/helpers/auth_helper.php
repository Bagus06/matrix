<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('check_auth')) {
    function check_auth()
    {

        $output = FALSE;
        $CI = &get_instance();
        $input_get = $CI->input->get();

        if (get_user()) {
            $redirect_url = decryptcst(@$input_get['redirect_url']);
            if (!empty($redirect_url)) {
                redirect($redirect_url);
            }

            $output = TRUE;
        } else {
            redirect(base_url() . 'login');
        }

        return $output;
    }
}
