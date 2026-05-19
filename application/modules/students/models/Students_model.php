<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Students_model extends CI_model
{
    protected $module_name = 'students';
    protected $error_prefix = 'STDN';
    protected $tb1 = 'students';
    protected $tb2 = 'universities';
    protected $tb3 = 'university_courses';
    protected $tb4 = 'leads';
    protected $tb5 = 'payments';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universities/universities_model');
    }

    public function detailed($item_id = 0, $whereclause = '')
    {
        $output  = false;

        if (!empty($whereclause) || !empty($item_id)) {
            $query = "SELECT $this->tb1.*, university_name, short_name, $this->tb2.city, course_code, course_name FROM $this->tb1";
            $query .= " INNER JOIN $this->tb2 ON $this->tb1.university_id = $this->tb2.id";
            $query .= " INNER JOIN $this->tb3 ON $this->tb1.course_id = $this->tb3.id";
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
                        $query_builder = $this->students(0, $get_params, "GET");
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
                $query_builder = $this->students(0, $get_params, "GET");
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

    public function students($item_id = 0, $datas = '', $type = '')
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
            $query .= " INNER JOIN $this->tb2 ON $this->tb1.university_id = $this->tb2.id";
            $query .= " INNER JOIN $this->tb3 ON $this->tb1.course_id = $this->tb3.id";
            $query .= " INNER JOIN $this->tb4 ON $this->tb1.enquiry_number = $this->tb4.enquiry_number";
            $query .= " LEFT JOIN $this->tb5 ON $this->tb1.student_number = $this->tb5.student_number";

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

    public function religion()
    {
        $output = [
            'Hindu',
            'Islam',
            'Kristen',
            'Sikh',
            'Buddha',
            'Jain',
            'OTHER'
        ];

        return $output;
    }

    public function university_report($parameters = null)
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

        $get_params = [
            'select' => 'students.*, university_courses.course_code, university_courses.course_name, payments.final_amount',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'student_number',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => true,
            'whereclause' => "students.university_id = '" . decryptcst(@$parameters['university_id']) . "' AND course_status = 'COMPLETED' AND completed_date BETWEEN '" . date('Y-m-d', strtotime(@$parameters['date_start'])) . "' AND '" . date('Y-m-d', strtotime(@$parameters['date_end'])) . "'"
        ];
        $datas_student = $this->students(0, $get_params, 'GET');
        $university_detailed = $this->universities_model->detailed(decryptcst(@$parameters['university_id']), '');

        if (!empty($datas_student['data']['data']) && !empty($university_detailed['data'])) {
            $base_path = FCPATH . "assets/export/";

            // create folder if not exists
            if (!is_dir($base_path)) {
                mkdir($base_path, 0775, true);
            }

            if (@$parameters['reportfor'] === 'university') {
                $filename = @$university_detailed['data']['university_name'] . "-" . date('Ymd') . '.xlsx';

                $path_file = $base_path . $filename;

                if (!copy(FCPATH . "assets/modules/" . $this->uri->rsegments[1] . "/template_export/university_report.xlsx", $path_file)) {
                }


                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path_file);

                $index = 1;
                $sheet = $spreadsheet->getActiveSheet();


                $sheet->setCellValue("A1", @$university_detailed['data']['university_name']);

                $row_number_start = 3;
                $row_number = $row_number_start;
                foreach ($datas_student["data"]['data'] as $key => $value) {

                    /** ----------------------------------------- Merge Row 1 ----------------------------------------- */
                    $sheet->setCellValue("A" . $row_number, $index);
                    $sheet->setCellValue("B" . $row_number, $value['full_name']);
                    $sheet->setCellValue("C" . $row_number, $value['father_name']);
                    $sheet->setCellValue("D" . $row_number, $value['mother_name']);
                    $sheet->setCellValue("E" . $row_number, date('d F Y', strtotime($value['date_of_birth'])));
                    $sheet->setCellValue("F" . $row_number, @$value['religion']);
                    $sheet->setCellValue("G" . $row_number, $value['gender']);
                    $sheet->setCellValue("H" . $row_number, ((!empty($value['state'])) ? $value['state'] : '-'));
                    $sheet->setCellValue("I" . $row_number, ((!empty($value['city'])) ? $value['city'] : '-'));
                    $sheet->setCellValue("J" . $row_number, $value['course_name'] . ' ( ' . $value['course_code'] . ' )');
                    $sheet->setCellValue("K" . $row_number, @$value['dept']);
                    $sheet->setCellValue("L" . $row_number, date('d F Y', strtotime($value['session'])));
                    $sheet->setCellValueExplicit("M" . $row_number, @$value['phone'], DataType::TYPE_STRING);
                    $sheet->setCellValue("N" . $row_number, @$value['email']);
                    $sheet->setCellValue("O" . $row_number, @$value['address']);

                    $student_photo = FCPATH . 'uploads/photo/' .  @$value['file_photo'];

                    if (is_file($student_photo)) {
                        $student_photo = FCPATH . '/uploads/photo/' .  @$value['file_photo'];
                    } else {
                        $student_photo = FCPATH . 'assets/img/profile/sample.png';
                    }

                    if (!empty($value['file_photo'])) {
                        $drawing = new Drawing();
                        $drawing->setName('Student Photo');
                        $drawing->setPath($student_photo);
                        $drawing->setHeight(100);
                        $drawing->setCoordinates("P" . $row_number);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(10);
                        $drawing->setWorksheet($sheet);
                        $sheet->getRowDimension($row_number)->setRowHeight(100);
                    } else {
                        $sheet->setCellValue("P" . $row_number, 'Empty.');
                    }

                    $row_number++;
                    $index++;
                }

                $style_column = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];

                $spreadsheet->getActiveSheet()->getStyle("A" . $row_number_start . ":P" . $row_number)->applyFromArray($style_column);
                $sheet->getColumnDimension('P')->setWidth(20);
            } elseif (@$parameters['reportfor'] === 'internal') {
                $filename = 'INTERNAL REPORT-' . @$university_detailed['data']['university_name'] . "-" . date('Ymd') . '.xlsx';

                $path_file = $base_path . $filename;

                if (!copy(FCPATH . "assets/modules/" . $this->uri->rsegments[1] . "/template_export/internal_report.xlsx", $path_file)) {
                }


                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path_file);

                $index = 1;
                $sheet = $spreadsheet->getActiveSheet();


                $sheet->setCellValue("A1", 'INTERNAL REPORT - ' . @$university_detailed['data']['university_name']);

                $row_number_start = 3;
                $row_number = $row_number_start;
                $total_fees = 0;
                foreach ($datas_student["data"]['data'] as $key => $value) {

                    /** ----------------------------------------- Merge Row 1 ----------------------------------------- */
                    $sheet->setCellValue("A" . $row_number, $index);
                    $sheet->setCellValue("B" . $row_number, $value['full_name']);
                    $sheet->setCellValue("C" . $row_number, $value['father_name']);
                    $sheet->setCellValue("D" . $row_number, $value['mother_name']);
                    $sheet->setCellValue("E" . $row_number, date('d F Y', strtotime($value['date_of_birth'])));
                    $sheet->setCellValue("F" . $row_number, @$value['religion']);
                    $sheet->setCellValue("G" . $row_number, $value['gender']);
                    $sheet->setCellValue("H" . $row_number, ((!empty($value['state'])) ? $value['state'] : '-'));
                    $sheet->setCellValue("I" . $row_number, ((!empty($value['city'])) ? $value['city'] : '-'));
                    $sheet->setCellValue("J" . $row_number, $value['course_name'] . ' ( ' . $value['course_code'] . ' )');
                    $sheet->setCellValue("K" . $row_number, @$value['dept']);
                    $sheet->setCellValue("L" . $row_number, date('d F Y', strtotime($value['session'])));
                    $sheet->setCellValueExplicit("M" . $row_number, @$value['phone'], DataType::TYPE_STRING);
                    $sheet->setCellValue("N" . $row_number, @$value['email']);
                    $sheet->setCellValue("O" . $row_number, @$value['address']);

                    $sheet->setCellValue("Q" . $row_number, @$value['final_amount']);
                    $total_fees += (float) $value['final_amount'];

                    $student_photo = FCPATH . 'uploads/photo/' .  @$value['file_photo'];

                    if (is_file($student_photo)) {
                        $student_photo = FCPATH . '/uploads/photo/' .  @$value['file_photo'];
                    } else {
                        $student_photo = FCPATH . 'assets/img/profile/sample.png';
                    }

                    if (!empty($value['file_photo'])) {
                        $drawing = new Drawing();
                        $drawing->setName('Student Photo');
                        $drawing->setPath($student_photo);
                        $drawing->setHeight(100);
                        $drawing->setCoordinates("P" . $row_number);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(10);
                        $drawing->setWorksheet($sheet);
                        $sheet->getRowDimension($row_number)->setRowHeight(100);
                    } else {
                        $sheet->setCellValue("P" . $row_number, 'Empty.');
                    }

                    $row_number++;
                    $index++;
                }

                $sheet->mergeCells("A$row_number:P$row_number");
                $sheet->setCellValue("A" . $row_number, 'TOTAL : ');
                $sheet->getStyle("A" . $row_number)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                $sheet->setCellValue("Q" . $row_number, $total_fees);
                $sheet->getStyle("Q" . $row_number)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                $style_column = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ]
                ];

                $spreadsheet->getActiveSheet()->getStyle("A" . $row_number_start . ":Q" . $row_number)->applyFromArray($style_column);
                $sheet->getColumnDimension('P')->setWidth(20);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            if ($writer->save($path_file) == '') {
                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'filename' => $filename,
                        'path' => $base_path
                    ]
                ];
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => "$this->error_prefix-URP-E001",
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
        } else {
            $output = [
                'status' => FALSE,
                'code' => "$this->error_prefix-URP-E002",
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

    private function document_upload($enquiry_number = null, $document_type = 'unidentified', $datas = null)
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
            if (!empty($enquiry_number)) {
                $enquiry_number = preg_replace('/\s+/', '-', trim(preg_replace('/[^a-zA-Z0-9\- ]/', '', @$enquiry_number)));
                $document_type  = preg_replace('/\s+/', '-', trim(preg_replace('/[^a-zA-Z0-9\- ]/', '', @$document_type)));

                $base_path = FCPATH . 'uploads/' . strtolower($document_type) . '/';

                // create folder if not exists
                if (!is_dir($base_path)) {
                    mkdir($base_path, 0775, true);
                }

                $ext = strtolower(pathinfo($datas['name'], PATHINFO_EXTENSION));

                $filename = strtolower($document_type) . '_' . strtolower(@$enquiry_number) . '.' . $ext;

                $upload_process = move_uploaded_file(@$datas['tmp_name'], $base_path . $filename);
                if ($upload_process) {
                    $output = [
                        'status' => TRUE,
                        'code' => null,
                        'replace_code_value' => null,
                        'redirectUrl' => null,
                        'debug' => null,
                        'data' => [
                            'enquiry_number' => strtolower($enquiry_number),
                            'document_type' => strtolower($document_type),
                            'filename' => $filename,
                            'bash_path' => $base_path . $filename
                        ]
                    ];
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => "$this->error_prefix-CNU-E003",
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

    private function document_remove($document_type = 'unidentified', $filename = null)
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

        if (!empty($filename)) {
            $base_path = FCPATH . 'uploads/' . strtolower($document_type) . '/';

            // create folder if not exists
            if (!is_dir($base_path)) {
                mkdir($base_path, 0775, true);
            }

            if (is_file($base_path . $filename)) {
                unlink($base_path . $filename);
            }

            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => null
            ];
        } else {
            $output = [
                'status' => FALSE,
                'code' => "$this->error_prefix-CNU-E004",
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
                $exist = $this->detailed(null, 'student_number = ' . $this->db->escape($input_post['item']) . " AND id != $id");

                $output = $exist;
                if ($exist['status']) {
                    if (!empty($exist['data'])) {
                        if (@$input_post['replace']) {
                            $delete = $this->students($exist['data']['id'], 0, 'DELETE');

                            $output = $delete;
                            if ($delete['status']) {
                                $output = $this->students($id, ['row_status' => 1, 'updated_by' => get_user()['id']], 'PATCH');
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
                    $output = $this->students($id, ['row_status' => 1, 'updated_by' => get_user()['id']], 'PATCH');
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
                'student_number' => substr(strtoupper($datas['student_number']), 0, 15),
                'university_id' => @$datas['university_id'],
                'course_id' => @$datas['course_id'],
                'dept' => substr(@$datas['dept'], 0, 255),
                'course_status' => substr(strtoupper(@$datas['course_status']), 0, 20),
                'session' => date('Y-m-d', strtotime(@$datas['session'])),
                'enquiry_number' => substr(strtoupper(@$datas['enquiry_number']), 0, 15),
                'final_fees' => round((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $datas['final_fees'])) ? $amount : 0, 2),
                'additional_certificate' => @$datas['additional_certificate'],
                'additional_certificate_fee' => round((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $datas['additional_certificate_fee'])) ? $amount : 0, 2),
                'full_name' => substr($datas['first_name'] . ((!empty($datas['last_name'])) ? ' ' . trim($datas['last_name']) : ''), 0, 100),
                'first_name' => substr($datas['first_name'], 0, 50),
                'last_name' => substr(@$datas['last_name'], 0, 50),
                'date_of_birth' => date('Y-m-d', strtotime($datas['date_of_birth'])),
                'aadhaar_number' => substr(@$datas['aadhaar_number'], 0, 12),
                'father_name' => substr(strtoupper(@$datas['father_name']), 0, 100),
                'mother_name' => substr(strtoupper(@$datas['mother_name']), 0, 100),
                'religion' => substr(strtoupper(@$datas['religion']), 0, 50),
                'gender' => substr(@$datas['gender'], 0, 10),
                'email' => substr(@$datas['email'], 0, 100),
                'phone' => substr(preg_replace('/[^0-9]/', '', @$datas['phone']), 0, 100),
                'whatsapp_number' => substr(preg_replace('/[^0-9]/', '', @$datas['whatsapp_number']), 0, 100),
                'country_id' => $datas['country_id'],
                'state_id' => $datas['state_id'],
                'city_id' => $datas['city_id'],
                'district_id' => $datas['district_id'],
                'address' => $datas['address'],
                'postal_code' => substr($datas['postal_code'], 0, 50),
                'updated_by' => get_user()['id']
            ];

            $leads_detailed = $this->leads_model->detailed('', "enquiry_number = '" . @$data_post['enquiry_number'] . "'");
            $exist = $this->detailed(null, "$this->tb1.row_status = 1 AND aadhaar_number = " . $this->db->escape($data_post['aadhaar_number']) . " AND $this->tb1.university_id = " . $this->db->escape(@$data_post['university_id']) . " AND $this->tb1.course_id = " . $this->db->escape(@$data_post['course_id']));

            /* ========================= Section Upload Document ========================= */

            # Upload file_aadhaar
            if (!empty($_FILES['file_aadhaar']['name'])) {
                $aadhaar_upload = $this->document_upload($data_post['enquiry_number'], 'aadhaar', $_FILES['file_aadhaar']);
                if ($aadhaar_upload['status']) {
                    $data_post['file_aadhaar'] = @$aadhaar_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_aadhaar'])) {
                $data_post['file_aadhaar'] =  $exist['data']['file_aadhaar'];
            } elseif (!empty($leads_detailed['data']['file_aadhaar'])) {
                $data_post['file_aadhaar'] =  $leads_detailed['data']['file_aadhaar'];
            }

            # Upload file_photo
            if (!empty($_FILES['file_photo']['name'])) {
                $photo_upload = $this->document_upload($data_post['enquiry_number'], 'photo', $_FILES['file_photo']);
                if ($photo_upload['status']) {
                    $data_post['file_photo'] = @$photo_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_photo'])) {
                $data_post['file_photo'] =  $exist['data']['file_photo'];
            } elseif (!empty($leads_detailed['data']['file_photo'])) {
                $data_post['file_photo'] =  $leads_detailed['data']['file_photo'];
            }

            # Upload file_certificate
            if (!empty($_FILES['file_certificate']['name'])) {
                $certificate_upload = $this->document_upload($data_post['enquiry_number'], 'certificate', $_FILES['file_certificate']);
                if ($certificate_upload['status']) {
                    $data_post['certificate'] = @$certificate_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['certificate'])) {
                $data_post['certificate'] =  $exist['data']['certificate'];
            }

            # Upload file_certificate1
            if (!empty($_FILES['file_certificate1']['name'])) {
                $certificate1_upload = $this->document_upload($data_post['enquiry_number'], 'certificate1', $_FILES['file_certificate1']);
                if ($certificate1_upload['status']) {
                    $data_post['file_certificate1'] = @$certificate1_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_certificate1'])) {
                $data_post['file_certificate1'] =  $exist['data']['file_certificate1'];
            } elseif (!empty($leads_detailed['data']['file_certificate1'])) {
                $data_post['file_certificate1'] =  $leads_detailed['data']['file_certificate1'];
            }

            # Upload file_certificate2
            if (!empty($_FILES['file_certificate2']['name'])) {
                $certificate2_upload = $this->document_upload($data_post['enquiry_number'], 'certificate2', $_FILES['file_certificate2']);
                if ($certificate2_upload['status']) {
                    $data_post['file_certificate2'] = @$certificate2_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_certificate2'])) {
                $data_post['file_certificate2'] =  $exist['data']['file_certificate2'];
            } elseif (!empty($leads_detailed['data']['file_certificate2'])) {
                $data_post['file_certificate2'] =  $leads_detailed['data']['file_certificate2'];
            }

            # Upload file_certificate3
            if (!empty($_FILES['file_certificate3']['name'])) {
                $certificate3_upload = $this->document_upload($data_post['enquiry_number'], 'certificate3', $_FILES['file_certificate3']);
                if ($certificate3_upload['status']) {
                    $data_post['file_certificate3'] = @$certificate3_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_certificate3'])) {
                $data_post['file_certificate3'] =  $exist['data']['file_certificate3'];
            } elseif (!empty($leads_detailed['data']['file_certificate3'])) {
                $data_post['file_certificate3'] =  $leads_detailed['data']['file_certificate3'];
            }

            # Upload file_certificate4
            if (!empty($_FILES['file_certificate4']['name'])) {
                $certificate4_upload = $this->document_upload($data_post['enquiry_number'], 'certificate4', $_FILES['file_certificate4']);
                if ($certificate4_upload['status']) {
                    $data_post['file_certificate4'] = @$certificate4_upload['data']['filename'];
                }
            } elseif (!empty($exist['data']['file_certificate4'])) {
                $data_post['file_certificate4'] =  $exist['data']['file_certificate4'];
            } elseif (!empty($leads_detailed['data']['file_certificate4'])) {
                $data_post['file_certificate4'] =  $leads_detailed['data']['file_certificate4'];
            }

            /* ===================================================================================== */

            // Unset key if datas is empty
            if (empty($datas['enquiry_number'])) {
                unset($data_post['enquiry_number']);
            }

            if (empty($datas['university_id'])) {
                unset($data_post['university_id']);
            }

            if (empty($datas['course_id'])) {
                unset($data_post['course_id']);
            }

            if (empty($datas['date_of_birth'])) {
                unset($data_post['date_of_birth']);
            }

            if (empty($datas['aadhaar_number'])) {
                unset($data_post['aadhaar_number']);
            }

            if (empty($datas['religion'])) {
                unset($data_post['religion']);
            }

            if (empty($datas['gender'])) {
                unset($data_post['gender']);
            }

            if (empty($datas['course_status'])) {
                unset($data_post['course_status']);
                $data_post['completed_date'] = NULL;

                if (!empty($exist['data']['course_status'])) {
                    $data_post['course_status'] = NULL;
                }
            } else {
                if (strtoupper($datas['course_status']) === 'COMPLETED') {
                    $data_post['completed_date'] = date('Y-m-d');
                }
            }

            # Set string geolocation
            if (!empty($data_post['country_id'])) {
                $country = $this->countries_model->detailed($data_post['country_id']);
                if (!empty($country['data']['name'])) {
                    $data_post['country'] = $country['data']['name'];
                }
            } else {
                unset($data_post['country_id']);
            }
            if (!empty($data_post['state_id'])) {
                $state = $this->states_model->detailed($data_post['state_id']);
                if (!empty($state['data']['name'])) {
                    $data_post['state'] = $state['data']['name'];
                }
            } else {
                unset($data_post['state_id']);
            }
            if (!empty($data_post['city_id'])) {
                $city = $this->cities_model->detailed($data_post['city_id']);
                if (!empty($city['data']['name'])) {
                    $data_post['city'] = $city['data']['name'];
                }
            } else {
                unset($data_post['city_id']);
            }
            if (!empty($data_post['district_id'])) {
                $district = $this->districts_model->detailed($data_post['district_id']);
                if (!empty($district['data']['name'])) {
                    $data_post['district'] = $district['data']['name'];
                }
            } else {
                unset($data_post['district_id']);
            }

            if (!empty($id)) {

                /* ====================== Section remove document ====================== */

                if (!empty($datas['remove_file_aadhaar'])) {
                    $data_post['file_aadhaar'] = NULL;
                    $remove_document = $this->document_remove('aadhaar', @$exist['data']['file_aadhaar']);
                }

                if (!empty($datas['remove_file_photo'])) {
                    $data_post['file_photo'] = NULL;
                    $remove_document = $this->document_remove('photo', @$exist['data']['file_photo']);
                }

                if (!empty($datas['remove_file_certificate'])) {
                    $data_post['certificate'] = NULL;
                    $remove_document = $this->document_remove('certificate', @$exist['data']['certificate']);
                }

                if (!empty($datas['remove_file_certificate1'])) {
                    $data_post['file_certificate1'] = NULL;
                    $remove_document = $this->document_remove('certificate1', @$exist['data']['file_certificate1']);
                }

                if (!empty($datas['remove_file_certificate2'])) {
                    $data_post['file_certificate2'] = NULL;
                    $remove_document = $this->document_remove('certificate2', @$exist['data']['file_certificate2']);
                }

                if (!empty($datas['remove_file_certificate3'])) {
                    $data_post['file_certificate3'] = NULL;
                    $remove_document = $this->document_remove('certificate3', @$exist['data']['file_certificate3']);
                }

                if (!empty($datas['remove_file_certificate4'])) {
                    $data_post['file_certificate4'] = NULL;
                    $remove_document = $this->document_remove('certificate4', @$exist['data']['file_certificate4']);
                }

                /* ===================================================================== */

                // Unilink university and course if empty value
                if (empty($datas['university_id'])) {
                    unset($data_post['university_id']);
                }
                if (empty($datas['course_id'])) {
                    unset($data_post['course_id']);
                }

                if (($id == @$exist['data']['id']) || empty($exist['data'])) {
                    $output = $this->students($id, $data_post, 'PATCH');
                    if (!empty($output['data'])) {
                        $output['data']['update'] = true;
                    }
                } else {
                    $output = [
                        'status' => TRUE,
                        'code' => "$this->error_prefix-CNU-E001",
                        'replace_code_value' => [
                            'message' => "The data '" . $data_post['full_name'] . "' already exists, please check the data again or enter other data"
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
                    $output = $this->students(null, $data_post, 'POST');
                } else {
                    $output = [
                        'status' => TRUE,
                        'code' => "$this->error_prefix-CNU-E001",
                        'replace_code_value' => [
                            'message' => "The data '" . $data_post['full_name'] . "' already exists, please check the data again or enter other data"
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
