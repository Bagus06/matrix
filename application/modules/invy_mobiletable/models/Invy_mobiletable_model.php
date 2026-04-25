<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invy_mobiletable_model extends CI_model
{
    protected $module_name = 'test';
    protected $tb1 = 'test_table';

    public function detailed($item_id = 0, $whereclause = '')
    {
        $output  = false;

        if (!empty($item_id)) {
            $item_id = decryptcst($item_id);

            $query = "SELECT * FROM $this->tb1";
            $query .= " WHERE $this->tb1.id = " . $this->db->escape($item_id);
            $query .= $whereclause;
            $excute_query = $this->db->query($query);

            if (empty($this->db->error()['code'])) {
                $data = $excute_query->row_array();

                if (!empty($data)) {
                    $output = [
                        'response' => true,
                        'error_message' => '',
                        'data' => $data
                    ];
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Data empty.',
                        'data' => $data
                    ];
                }
            } else {
                $output = [
                    'response' => false,
                    'error_message' => 'Failed get data. (' . $this->db->error()['message'] . "). Query : ($query)",
                    'data' => []
                ];
            }
        }

        return $output;
    }

    private function whereclause_system($bypass = false)
    {
        $output = '';

        if (!$bypass) {
        }

        return $output;
    }


    public function test($item_id = 0, $datas = '', $type = '')
    {
        $output = [
            'response' => false,
            'error_message' => 'No function executed.',
            'data' => []
        ];

        if (empty($type)) {
            $type = $_SERVER['REQUEST_METHOD'];
        }

        if (empty($datas)) {
            $datas = $this->input->post();
        }

        if (!empty($item_id)) {
            $item_id = decryptcst($item_id);
        }

        if (strtoupper($type) === 'POST') {
            $column = '';
            $values = '';

            foreach ($datas as $key => $value) {
                if (($value == 0) || !empty($value)) {
                    $column .= "$key , ";
                    $values .= $this->db->escape($value) . ', ';
                }
            }

            $column = '(' . substr($column, 0, -2) . ')';
            $values = '(' . substr($values, 0, -2) . ')';

            $query = "INSERT INTO $this->tb1 $column VALUE $values";
            if ($this->db->query($query)) {
                $output = [
                    'response' => true,
                    'error_message' => '',
                    'data' => [
                        'insert_id' => $this->db->insert_id(),
                        'query_excuted' => $query
                    ],
                ];
            } else {
                $output = [
                    'response' => false,
                    'error_message' => 'Failed to insert data. (' . $this->db->error()['message'] . "). Query : ($query)",
                    'data' => []
                ];
            }
        } elseif (strtoupper($type) === 'PATCH') {
            if (!empty($item_id)) {
                $item_detailed = $this->detailed(encryptcst($item_id));

                if ($item_detailed['response']) {
                    $item_detailed = $item_detailed['data'];
                    $values = '';

                    foreach ($datas as $key => $value) {

                        # Check for the same value. If there is a similarity in the value between the one in the database and the one to be input, the input will not be processed.
                        if ((($value == 0) || !empty($value)) && (@$item_detailed[$key] != $value)) {
                            $values .= "$key = " . $this->db->escape($value) . ', ';
                        }
                    }

                    if (!empty($values)) {
                        $query = "UPDATE $this->tb1 SET " .  substr($values, 0, -2)  . " WHERE id = $item_id";
                        if ($this->db->query($query)) {
                            $output = [
                                'response' => true,
                                'error_message' => '',
                                'data' => [
                                    'effected_id' => $item_id,
                                    'query_excuted' => $query
                                ],
                            ];
                        } else {
                            $output = [
                                'response' => false,
                                'error_message' => 'Failed to Patch data. (' . $this->db->error()['message'] . "). Query : ($query)",
                                'data' => [
                                    'effected_id' => $item_id,
                                    'query_excuted' => $query
                                ]
                            ];
                        }
                    } else {
                        $output = [
                            'response' => true,
                            'error_message' => 'No data has been changed.',
                            'data' => []
                        ];
                    }
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'item_id is empty or data not found.',
                        'data' => []
                    ];

                    return $output;
                }
            } else {
                $output = [
                    'response' => false,
                    'error_message' => 'item_id is empty or data not found.',
                    'data' => []
                ];

                return $output;
            }
        } elseif (strtoupper($type) === 'GET') {
            /* required datas
            | !select
            | !row_status (1 -> data active or 0 -> data self delete)
            | !outputtype (data or query)
            | ?order_by [!column !order (ASC, DSC)]
            | ?limit [!length & !start]
            | ?bypass (true or false)
            | ?whereclause
            */

            $row_status = (!empty($datas["row_status"])) ? $datas["row_status"] : 1;

            $query = '';
            $query .= 'SELECT ' . @$datas['select'] . " FROM $this->tb1";

            $query .= " WHERE $this->tb1.row_status = $row_status";
            $query .= $this->whereclause_system($datas["bypass"]);

            if (@$datas["outputtype"] === 'data') {
                $excute_query = $this->db->query($query);
                if (empty($this->db->error()['code'])) {
                    $all_record = $excute_query->num_rows();
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Failed excute query. (' . $this->db->error()['message'] . "). Query : ($query)",
                        'data' => []
                    ];

                    return $output;
                }
            }

            if (!empty($datas["whereclause"])) {
                $query .= ' AND (' . $datas["whereclause"] . ')';
            }

            if (!empty($datas["order_by"]["column"])) {
                $query .= ' ORDER BY ' . $datas["order_by"]["column"] . ' ' . $datas["order_by"]["order"];
            }

            if (@$datas["outputtype"] === 'data') {
                $excute_query = $this->db->query($query);
                if (empty($this->db->error()['code'])) {
                    $filterd = $excute_query->num_rows();
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Failed excute query. (' . $this->db->error()['message'] . "). Query : ($query)",
                        'data' => []
                    ];

                    return $output;
                }
            }

            if (@$datas["limit"]["length"] > 0) {
                $query .= ' LIMIT ' . $datas["limit"]["length"] . ' OFFSET ' . $datas["limit"]["start"];
            } elseif (@$datas["limit"]["length"] <= 0) {
                $query .= '';
            } else {
                $query .= ' LIMIT 10';
            }

            if (@$datas["outputtype"] === 'query') {
                if ($this->db->simple_query($query)) {
                    $output = $query;
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Failed excute query. (' . $this->db->error()['message'] . "). Query : ($query)",
                        'data' => []
                    ];

                    return $output;
                }
            } elseif (@$datas["outputtype"] === 'data') {
                $excute_query = $this->db->query($query);
                if (empty($this->db->error()['code'])) {
                    $output = [
                        'response' => true,
                        'error_message' => '',
                        'data' => [
                            'data' => $excute_query->result_array(),
                            'all_record' => $all_record,
                            'filtered_record' =>  $filterd
                        ]
                    ];
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Failed excute query. (' . $this->db->error()['message'] . "). Query : ($query)",
                        'data' => []
                    ];

                    return $output;
                }
            }
        } elseif (strtoupper($type) === 'DELETE') {
            $query = "";

            if (!empty($item_id)) {
                if ($datas === 1) {
                    $query = "UPDATE $this->tb1 SET row_status = 0, updated_by = " . get_user()["id"] . " WHERE id = $item_id";
                } elseif ($datas === 0) {
                    $query = "DELETE FROM $this->tb1 WHERE id = $item_id";
                }

                if ($this->db->query($query)) {
                    $output = [
                        'response' => true,
                        'error_message' => '',
                        'data' => [
                            'effected_id' => $item_id
                        ]
                    ];
                } else {
                    $output = [
                        'response' => false,
                        'error_message' => 'Failed excute query. (' . $this->db->error()['message'] . "). Query : ($query)",
                        'data' => []
                    ];
                }
            } else {
                $output = [
                    'response' => false,
                    'error_message' => 'item_id is empty or data not found.',
                    'data' => []
                ];

                return $output;
            }
        }

        return $output;
    }
}
