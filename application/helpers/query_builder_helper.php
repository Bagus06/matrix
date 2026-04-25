<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
if (!function_exists('ident_op')) {
    function ident_op($string)
    {
        $output = '';
        $CI = &get_instance();

        $string = trim($string);
        $operator = ['=', '!=', '<', '>', '<=', '>=', '<>'];
        if ((substr($string, -1) == '%' && substr($string, 0, 1) == '%') || (substr($string, -1) == '%' || substr($string, 0, 1) == '%')) {
            $output = 'LIKE ' . $CI->db->escape($string);
        } elseif (in_array(substr($string, 0, 2), $operator) || in_array(substr($string, 0, 1), $operator)) {
            if (in_array(substr($string, 0, 2), $operator)) {
                $output = substr($string, 0, 2) . " '" . substr($string, 2) . "'";
            } elseif (in_array(substr($string, 0, 1), $operator)) {
                $output = substr($string, 0, 1) . " '" . substr($string, 1) . "'";
            }
        } elseif (($string === 'IS NULL') || ($string === 'IS NOT NULL')) {
            $output = $string;
        } else {
            $output = "LIKE '%" . $string . "%'";
        }

        return $output;
    }
}

if (!function_exists('wc_query_builder')) {
    function wc_query_builder($data)
    {
        $CI = &get_instance();
        $output = [
            'status' => FALSE,
            'code' => '',
            'replace_code_value' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => null
            ],
            'data' => null
        ];

        $wc = '';
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (!empty($value["colValue"])) {
                    $ex_value = explode(',', $value["colValue"]);
                    $loop = 0;
                    $countSrc = count($ex_value);
                    $key = (ctype_xdigit($value["column"])) ? str_replace('-', '.', hex2bin($value["column"])) : str_replace('-', '.', $value["column"]);
                    $key = str_replace("~", "-", $key);

                    if (@$value["type"] == "between") {
                        $ex_between = explode(" - ", $value["colValue"]);
                        if (!empty($ex_between[1])) {
                            $wc .= "$key BETWEEN  " . $CI->db->escape($ex_between[0]) . " AND " . $CI->db->escape($ex_between[1]);
                            $wc .= ' AND ';
                        }
                    } else {
                        if (!empty($ex_value[1])) {
                            foreach ($ex_value as $key1 => $value1) {
                                if ($loop == 0) {
                                    $wc .= '(';
                                    $wc .= $key . ' ' . ident_op($value1);
                                } elseif ($countSrc == $loop + 1) {
                                    $wc .= ' OR ';
                                    $wc .= $key . ' ' . ident_op($value1);
                                    $wc .= ')';
                                    $wc .= ' AND ';
                                } else {
                                    $wc .= ' OR ';
                                    $wc .= $key . ' ' . ident_op($value1);
                                }
                                $loop++;
                            }
                        } else {
                            $wc .= $key . ' ' . ident_op($value["colValue"]);
                            $wc .= ' AND ';
                        }
                    }
                }
            }

            if (empty($wc)) {
                $output = [
                    'status' => TRUE,
                    'code' => 'SYS-QB-E003',
                    'replace_code_value' => null,
                    'debug' => null,
                    'data' => null
                ];
            } else {
                if (substr(trim($wc), -3) == 'AND') {
                    $wc = trim(substr($wc, 0, -5));
                }

                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'debug' => null,
                    'data' => [
                        'wc_query' => $wc
                    ]
                ];
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => '',
                'replace_code_value' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => 'Data is empty.'
                ],
                'data' => null
            ];
        }

        return $output;
    }
}

function check_query($query)
{
    $output = false;
    $CI = &get_instance();

    if ($CI->db->simple_query($query)) {
        $output = [
            "success" => true,
            "error_message" => "",
            "data" => [
                'query' => $query
            ]
        ];
    } else {
        $output = [
            "success" => false,
            "error_message" => 'Error Code : ' . @$CI->db->error()["code"] . '\nError Message : ' . @$CI->db->error()["message"],
            "data" => []
        ];
    }

    return $output;
}
