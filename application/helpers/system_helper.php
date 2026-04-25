<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('dd')) {
    function dd($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }
}

if (!function_exists('user_ag')) {
    function user_ag()
    {
        $CI = &get_instance();
        $CI->load->library('user_agent');
        $ret = '';

        if ($CI->agent->is_mobile()) {
            $ret = 'mobile';
        } else {
            $ret = 'desktop';
        }

        return $ret;
    }
}
