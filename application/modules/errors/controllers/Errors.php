<?php defined('BASEPATH') or exit('No direct script access allowed');
class Errors extends CI_Controller
{
    public function get_error_info($code = '')
    {
        $input_get = @$this->input->get();

        $error = ((empty($code)) ? ((empty($input_get['error']))  ? '' : json_decode(urldecode($input_get['error']), true)) : $code);
        if (empty($error)) {
            echo json_encode('Invalid, fill code path param.');
        } else {
            echo json_encode(get_error_info($error));
        }
    }
}
