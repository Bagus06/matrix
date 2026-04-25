<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ErrorCodeHook
{
    public function inject_error_codes()
    {
        $CI = &get_instance();
        $CI->load->config('error_codes');
        $CI->load->vars('error_codes', $CI->config->item('error_codes'));
    }
}
