<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Generate full JSON response for frontend
 * 
 * @param string $codePath example : MODULE-FUNCTION-CODE
 */
if (!function_exists('get_error_info')) {
    function get_error_info($params = null)
    {
        $CI = &get_instance();
        $CI->load->config('error_codes', TRUE);
        $errors = $CI->config->item('error_codes');

        $code = $params;
        if (is_array($params)) {
            if (empty($params['status'])) {
                if (!empty($params['debug'])) {
                    sys_error_logs($params);
                }
            }

            if (!empty($params['code'])) {
                $code = $params['code'];
            } else {
                $code = 'SYS-BUG-E002';
            }
        }
        $parts = explode('-', $code);

        if (isset($errors[@$parts[0]][@$parts[1]][@$parts[2]])) {
            $data = $errors[@$parts[0]][@$parts[1]][@$parts[2]];

            if (is_array($params)) {
                if (!empty($params['replace_code_value'])) {
                    foreach ($params['replace_code_value'] as $key => $value) {
                        $data[$key] = $value;
                    }
                }
            }
        } else {
            return [
                'code' => $code,
                'message' => 'Unknown error code.',
                'level' => 'info'
            ];
        }

        return array_merge(['code' => $code], $data);
    }
}

if (!function_exists('sys_error_logs')) {
    function sys_error_logs($params = null)
    {
        $output = FALSE;
        $CI = &get_instance();

        if (!empty($params['debug'])) {
            $query = "INSERT INTO sys_error_logs (user_id, file, line, hint) VALUES (" . @get_user()["id"] . ", " . $CI->db->escape(@$params['debug']["file"]) . ", " . @$params['debug']["line"] . ", " . $CI->db->escape(@$params['debug']["hint"]) . ")";
            $excute_query = $CI->db->query($query);

            if (empty($CI->db->error()['code'])) {
                $output = TRUE;
            }
        }

        return $output;
    }
}
