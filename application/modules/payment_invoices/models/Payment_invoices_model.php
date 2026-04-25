<?php defined('BASEPATH') or exit('No direct script access allowed');

class Payment_invoices_model extends CI_model
{
    protected $module_name = 'payment_invoices';
    protected $error_prefix = 'INVC';
    protected $tb1 = 'payment_invoices';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users/users_model');
        $this->load->model('leads_sources/leads_sources_model');
        $this->load->model('university_courses/university_courses_model');
    }

    public function detailed($item_id = 0, $whereclause = '')
    {
        $output  = false;

        if (!empty($whereclause) || !empty($item_id)) {
            $query = "SELECT * FROM $this->tb1";
            $query .= ' WHERE ';
            if (empty($whereclause)) {
                $query .= "$this->tb1.id = " . $this->db->escape($item_id);
            } else {
                $query .= $whereclause;
            }

            $excute_query = $this->db->query($query);

            if (empty($this->db->error()['code'])) {
                $data = $excute_query->row_array();

                if (!empty($data)) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => $data
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => "$this->error_prefix-DTL-E001",
                        'replace_code_value' => [
                            'hint' => $query
                        ],
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => null
                    ];
                }
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => "$this->error_prefix-DTL-E002",
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Failed get data. (' . $this->db->error()['message'] . "). Query : ($query)"
                    ],
                    'data' => null
                ];
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => "$this->error_prefix-DTL-E003",
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => 'ID and WhereClause are empty'
                ],
                'data' => null
            ];
        }

        return $output;
    }

    public function query_builder($data)
    {
        $output = null;

        if (!empty($data)) {
            if (is_array(@$data["search"])) {
                $wc_query = wc_query_builder($data["search"]);
                if ($wc_query['status']) {
                    if (empty($wc_query['data'])) {
                        $output = $wc_query;
                        return $output;
                    } else {
                        $get_params = [
                            "select" => "$this->tb1.id",
                            "row_status" => $data["row_status"],
                            "outputtype" => "query",
                            "order_by" => [
                                "column" => "",
                                "order" => ""
                            ],
                            "limit" => [
                                "length" => 1,
                                "start" => 0
                            ],
                            'bypass' => false,
                            "whereclause" => @$wc_query['data']['wc_query']
                        ];
                        $query_builder = $this->invoices(0, $get_params, "GET");
                    }
                } else {
                    $output = $wc_query;
                    return $output;
                }
            } else {
                $get_params = [
                    "select" => "$this->tb1.id",
                    "row_status" => $data["row_status"],
                    "outputtype" => "query",
                    "order_by" => [
                        "column" => "",
                        "order" => ""
                    ],
                    "limit" => [
                        "length" => 1,
                        "start" => 0
                    ],
                    'bypass' => false,
                    "whereclause" => @$data["search"]
                ];
                $query_builder = $this->invoices(0, $get_params, "GET");
            }

            if (!empty($query_builder['data'])) {
                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'wc_query' => @$wc_query['data']['wc_query']
                    ]
                ];
            } else {
                $output = @$query_builder;
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
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

    private function whereclause_system($params = [])
    {
        $output = '';

        return $output;
    }

    public function invoices($item_id = 0, $datas = '', $type = '')
    {
        $output = [
            'status' => FALSE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => ''
            ],
            'data' => null

        ];

        if (empty($type)) {
            $type = $_SERVER['REQUEST_METHOD'];
        }

        if (empty($datas) && ($datas != FALSE)) {
            $datas = $this->input->post();
        }

        if (strtoupper($type) === 'POST') {
            $column = '';
            $values = '';

            if (!empty($datas)) {
                foreach ($datas as $key => $value) {
                    if (($value == 0) || !empty($value)) {
                        $column .= "$key , ";
                        $values .= $this->db->escape($value) . ', ';
                    }
                }

                $column = '(' . substr($column, 0, -2) . ')';
                $values = '(' . substr($values, 0, -2) . ')';

                $query = "INSERT INTO $this->tb1 $column VALUE $values";
                $query_excuted = $this->db->query($query);

                if (empty($this->db->error()['code'])) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => [
                            'insert_id' => $this->db->insert_id()
                        ]
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                        ],
                        'data' => null
                    ];
                }
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => '',
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Datas is empty.'
                    ],
                    'data' => null
                ];
            }
        } elseif (strtoupper($type) === 'PATCH') {
            if (!empty($item_id)) {
                $item_detailed = $this->detailed($item_id);

                if (empty($item_detailed['data'])) {
                    return $item_detailed;
                } else {
                    $values = '';
                    foreach ($datas as $key => $value) {
                        // Check for the same value. If there is a similarity in the value between the one in the database and the one to be input, the input will not be processed.
                        if ((($value == 0) || !empty($value)) && (@$item_detailed['data'][$key] != $value)) {
                            $values .= "$key = " . $this->db->escape($value) . ', ';
                        }
                    }

                    if (!empty($values)) {
                        $query = "UPDATE $this->tb1 SET " .  substr($values, 0, -2)  . " WHERE id = $item_id";
                        $query_excuted = $this->db->query($query);

                        if (empty($this->db->error()['code'])) {
                            $output = [
                                'status' => TRUE,
                                'code' => null,
                                'replace_code_vallue' => null,
                                'redirectUrl' => null,
                                'debug' => null,
                                'data' => [
                                    'effected_id' => $item_id,
                                    'query_excuted' => $query
                                ],
                            ];
                        } else {
                            $output = [
                                'status' => FALSE,
                                'code' => null,
                                'replace_code_value' => null,
                                'redirectUrl' => null,
                                'debug' => [
                                    'file' => __FILE__,
                                    'line' => __LINE__,
                                    'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                                ],
                                'data' => null
                            ];
                        }
                    } else {
                        $output = [
                            'status' => TRUE,
                            'code' => "$this->error_prefix-PTC-E001",
                            'replace_code_value' => null,
                            'redirectUrl' => null,
                            'debug' => [
                                'file' => __FILE__,
                                'line' => __LINE__,
                                'hint' => 'The data value to be updated is empty.'
                            ],
                            'data' => null
                        ];
                    }
                }
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Empty ID'
                    ],
                    'data' => null
                ];
            }

            return $output;
        } elseif (strtoupper($type) === 'GET') {
            /* required datas
            * [select] = Enter the name of the data column that will be called in the format, 'column1, column2, etc.'
            * [row_status] = The value of the data type is Boolean, if the value is FALSE then the data that goes into the recycle bin will be taken. Default value 'TRUE'.
            * [outputtype] = Enter the string value data type 'data' if you want to retrieve the data that is 'GET'. Enter 'query' if you only want to retrieve the query string that has been created.
            * [order_by] = The value data type is array example ['column1 order,' column2 order]. For column, enter the name of the column to be sorted and for order, enter the string type ASC or DESC. Enter 'ASC' to sort the data from smallest to largest and enter 'DESC' to sort the data from largest to smallest.
            * [bypass] = A Boolean value. Enter 'TRUE' to retrieve all data without being restricted by the system's default where clause. Note: the program may overprocess and crash due to retrieving too much data.
            * [limit] = Example of an array of data types values ​​['length' => 10, 'start' => 0] enter values ​​with integer data types. Enter the value -1 if you want to retrieve all data without limitation.
            * [whereclause] = Enter the whereclause query according to the SQL query rules, with the String data type.
            */

            $row_status = ((empty($datas["row_status"]) && ($datas['row_status'] != FALSE)) ? TRUE : $datas["row_status"]);

            $query = '';
            $query .= 'SELECT ' . @$datas['select'] . " FROM $this->tb1";

            $query .= " WHERE $this->tb1.row_status = $row_status";
            $query .= $this->whereclause_system($datas["bypass"]);

            if (@$datas["outputtype"] === 'data') {
                $excute_query = $this->db->simple_query($query);
                if (empty($this->db->error()['code'])) {
                    $all_record = $this->db->query($query)->num_rows();
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                        ],
                        'data' => null
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
                $excute_query = $this->db->simple_query($query);
                if (empty($this->db->error()['code'])) {
                    $filterd = $this->db->query($query)->num_rows();
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                        ],
                        'data' => null
                    ];

                    return $output;
                }
            } elseif (@$datas["outputtype"] !== 'query') {
                $output = [
                    'status' => FALSE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => '[Outputtype] empty or not the same as the parameters that have been determined. Fill the parameter with the value "data" or "query".'
                    ],
                    'data' => null
                ];

                return $output;
            }

            if (@$datas["limit"]["length"] > 0) {
                $query .= ' LIMIT ' . $datas["limit"]["length"] . ' OFFSET ' . $datas["limit"]["start"];
            } elseif (@$datas["limit"]["length"] <= 0) {
                $query .= '';
            } else {
                $query .= ' LIMIT 10';
            }

            if (@$datas["outputtype"] === 'query') {
                $excute_query = $this->db->simple_query($query);
                if (empty($this->db->error()['code'])) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => [
                            'query' => $query
                        ]
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                        ],
                        'data' => null
                    ];
                }
            } elseif (@$datas["outputtype"] === 'data') {
                $excute_query = $this->db->simple_query($query);
                if (empty($this->db->error()['code'])) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => [
                            'data' => $this->db->query($query)->result_array(),
                            'all_record' => $all_record,
                            'filtered_record' =>  $filterd
                        ]
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $this->db->error()['code'] . ')'
                        ],
                        'data' => null
                    ];
                }
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => '[Outputtype] empty or not the same as the parameters that have been determined. Fill the parameter with the value "data" or "query".'
                    ],
                    'data' => null
                ];
            }
        } elseif (strtoupper($type) === 'DELETE') {
            $query = "";

            if (!empty($item_id)) {
                if ($datas == 1) {
                    $query = "UPDATE $this->tb1 SET row_status = 0, updated_by = " . get_user()["id"] . " WHERE id = $item_id";
                } elseif ($datas == 0) {
                    $query = "DELETE FROM $this->tb1 WHERE id = $item_id";
                }
                $excute_query = $this->db->query($query);

                if (empty($this->db->error()['code'])) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_message' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => [
                            'effected_id' => $item_id,
                            'query_excuted' => $query
                        ]
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => "$this->error_prefix-DEL-001",
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => [
                            'file' => __FILE__,
                            'line' => __LINE__,
                            'hint' => $this->db->error()['message'] . '. Query (' . $query . ')'
                        ],
                        'data' => null
                    ];
                }
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => "$this->error_prefix-DEL-002",
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Empty ID'
                    ],
                    'data' => null
                ];
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => '[Outputtype] empty or not the same as the parameters that have been determined. Fill the parameter with the value "data" or "query".'
                ],
                'data' => null
            ];
        }

        return $output;
    }

    public function restore($id)
    {
        $output = [
            'status' => FALSE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => ''
            ],
            'data' => null
        ];

        $input_post = @$this->input->post();
        if (!empty($id)) {
            $detailed = $this->detailed($id);

            $output = $detailed;
            if (!empty($detailed['data'])) {
                $exist = $this->detailed(null, 'invoice_number = ' . $this->db->escape($input_post['item']) . " AND id != $id");

                $output = $exist;
                if ($exist['status']) {
                    if (!empty($exist['data'])) {
                        if (@$input_post['replace']) {
                            $delete = $this->invoices($exist['data']['id'], 0, 'DELETE');

                            $output = $delete;
                            if ($delete['status']) {
                                $output = $this->invoices($id, ['row_status' => 1, 'updated_by' => get_user()['id']], 'PATCH');
                            }
                        } else {
                            $output = [
                                'status' => TRUE,
                                'code' => "$this->error_prefix-RST-E002",
                                'replace_code_value' => null,
                                'redirectUrl' => null,
                                'debug' => null,
                                'data' => [
                                    'duplicate' => true
                                ]
                            ];
                        }
                    }
                } else {
                    $output = $this->invoices($id, ['row_status' => 1, 'updated_by' => get_user()['id']], 'PATCH');
                }
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => "$this->error_prefix-RST-E001",
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => 'Empty ID'
                ],
                'data' => null
            ];
        }

        return $output;
    }

    public function create_and_edit($id = null, $datas = null)
    {
        $output = [
            'status' => FALSE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => ''
            ],
            'data' => null
        ];

        if (!empty($datas)) {
            $data_post = [
                'student_number' => substr($datas['student_number'], 0, 15),
                'invoice_number' => substr($datas['invoice_number'], 0, 15),
                'invoice_date' => date('Y-m-d', strtotime(@$datas['invoice_date'])),
                'information' => @$datas['information'],
                'amount' => number_format((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $datas['amount'])) ? $amount : 0, 2),
                'tax_percent' => number_format((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $datas['tax_percent'])) ? $amount : 0, 2),
                'final_amount' => number_format((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $datas['final_amount'])) ? $amount : 0, 2),
                'request_by' => @$datas['request_by'],
                'request_date' => date('Y-m-d', strtotime(@$datas['request_date'])),
                'approval_status' => substr(@$datas['approval_status'], 0, 50),
                'approval_by' => @$datas['approval_by'],
                'approval_date' => date('Y-m-d', strtotime(@$datas['approval_date'])),
                'sending_status' => substr(@$datas['sending_status'], 0, 50),
                'updated_by' => get_user()['id']
            ];

            if (empty($datas['invoice_date'])) {
                unset($data_post['invoice_date']);
            }

            if (empty($datas['approval_status'])) {
                unset($data_post['approval_status']);
            }

            if (empty($datas['sending_status'])) {
                unset($data_post['sending_status']);
            }

            if (empty($datas['information'])) {
                unset($data_post['information']);
            }

            if (!empty($data_post['request_by'])) {
                $detailed_requestor = $this->users_model->detailed($data_post['request_by']);
                if (!empty($detailed_requestor['data'])) {
                    $data_post['request_by_name'] = $detailed_requestor['data']['name'];
                }
            } else {
                unset($data_post['request_by']);
            }

            if (!empty($data_post['approval_by'])) {
                $detailed_requestor = $this->users_model->detailed($data_post['approval_by']);
                if (!empty($detailed_requestor['data'])) {
                    $data_post['approval_by_name'] = $detailed_requestor['data']['name'];
                }
            } else {
                unset($data_post['approval_by']);
            }
            $exist = $this->detailed(null, 'row_status = 1 AND invoice_number = ' . $this->db->escape($data_post['invoice_number']));

            if (!empty($id)) {

                if (empty($datas['invoice_date'])) {
                    unset($data_post['invoice_date']);
                }

                if (empty($datas['request_by'])) {
                    unset($data_post['request_by']);
                }

                if (($id == @$exist['data']['id']) || empty($exist['data'])) {
                    $output = $this->invoices($id, $data_post, 'PATCH');
                    if (!empty($output['data'])) {
                        $output['data']['update'] = true;
                    }
                } else {
                    $output = [
                        'status' => TRUE,
                        'code' => "$this->error_prefix-CNU-E001",
                        'replace_code_value' => [
                            'message' => "The data '" . $data_post['invoice_number'] . "' already exists, please check the data again or enter other data"
                        ],
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => null

                    ];
                }
            } else {
                if (empty($exist['data'])) {
                    $data_post['row_status'] = 1;
                    $data_post['created_by'] = get_user()['id'];
                    $output = $this->invoices(null, $data_post, 'POST');
                } else {
                    $output = [
                        'status' => TRUE,
                        'code' => "$this->error_prefix-CNU-E001",
                        'replace_code_value' => [
                            'message' => "The data '" . $data_post['invoice_number'] . "' already exists, please check the data again or enter other data"
                        ],
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => null

                    ];
                }
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => "$this->error_prefix-CNU-E002",
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => ''
                ],
                'data' => null
            ];
        }

        return $output;
    }
}
