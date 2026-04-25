<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

if (!function_exists('apps_conf')) {
    function apps_conf($conf)
    {
        $output = '';
        if ($conf === 'apps -v') {
            $output = '1.0.0';
        } elseif ($conf === 'apps -logo') {
            $output = base_url() . 'assets/img/logo/appslogo-512.png';
        } elseif ($conf === 'apps -favlogo') {
            $output = base_url() . 'assets/img/logo/logoonly v1.0.png';
        } elseif ($conf === 'company -title') {
            $output = 'MODWAY Academy';
        } elseif ($conf === 'company -blog') {
            $output = 'www.modway.co.in';
        } elseif ($conf === 'default_page_title') {
            $output = 'MATRIX';
        }

        return $output;
    }
}

if (!function_exists('remove_booked_number_not_used')) {
    function remove_booked_number_not_used()
    {
        $output = FALSE;
        $CI = &get_instance();

        $query = 'DELETE FROM apps_booked_number WHERE used = 0 AND updated_at <= DATE_SUB(NOW(), INTERVAL 1 MINUTE)';
        $delete = $CI->db->query($query);

        if (empty($CI->db->error()['code'])) {
            $output = TRUE;
        }

        return $output;
    }
}

if (!function_exists('last_booked_number')) {
    function last_booked_number($prefix = '', $incerement_digit = 0)
    {
        $output = FALSE;
        $CI = &get_instance();
        remove_booked_number_not_used();

        if (!empty($prefix) && !empty($incerement_digit)) {
            $query = "SELECT number FROM apps_booked_number WHERE number LIKE '$prefix%' ORDER BY id DESC LIMIT 1 OFFSET 0";
            $last_number = $CI->db->query($query)->row_array();

            if ($last_number) {
                $last = (int) substr($last_number['number'], -$incerement_digit);
                $next = $last + 1;
            } else {
                $next = 1;
            }

            $output = sprintf("$prefix%0" . $incerement_digit . "d", $next);
        }

        return $output;
    }
}

if (!function_exists('create_booked_number')) {
    function create_booked_number($number = null)
    {
        $output = FALSE;
        $CI = &get_instance();
        remove_booked_number_not_used();

        if (!empty($number)) {
            $query = "INSERT INTO `apps_booked_number`(`number`, `used`) VALUES ('$number', false)";
            $insert = $CI->db->query($query);

            if (empty($CI->db->error()['code'])) {
                $output = TRUE;
            }
        }

        return $output;
    }
}

if (!function_exists('update_booked_number')) {
    function update_booked_number($used = 0, $number = null)
    {
        $output = FALSE;
        $CI = &get_instance();
        if (!empty($number)) {
            $query = "UPDATE apps_booked_number SET used = $used, updated_at = " . $CI->db->escape(date('Y-m-d H:i:s')) . " WHERE number = " . $CI->db->escape($number);
            $update = $CI->db->query($query);

            if (empty($CI->db->error()['code'])) {
                $output = TRUE;
                remove_booked_number_not_used();
            }
        }

        return $output;
    }
}
