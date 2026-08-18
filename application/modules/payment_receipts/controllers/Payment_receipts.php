<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment_receipts extends CI_Controller
{
    protected $module = 'payment_receipts';
    protected $module_alias = 'RCT';
    protected $error_prefix = 'RECT';
    protected $default_column_order = array(null, 'receipt_number', 'invoice_number', 'student_number', null, 'amount', 'method_name', 'receipt_date');
    protected $default_order = [
        "column" => "receipt_number",
        "order" => "ASC"
    ];
    protected $default_column_select = 'payment_receipts.id, receipt_number, invoice_number, student_number, information, amount, method_name, receipt_date';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        sync_booked_number('receipt_number', 'payment_receipts');

        $this->load->model('payment_receipts_model');
        $this->load->model('students/students_model');
        $this->load->model('payments/payments_model');
        $this->load->model('payment_methods/payment_methods_model');
        $this->load->model('payment_invoices/payment_invoices_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->payment_receipts_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function main()
    {
        $internal = [
            'create_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'recycle_url' => ((permit_check('FT_' . $this->module_alias . '_RCY', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/recycle' : ''),
        ];
        $utilitys = null;

        $this->load->view('index', ['utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function generate_number()
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
        $prefix = '#RCT-' . date('Y');
        $booked_number = last_booked_number($prefix, 4);

        $create = create_booked_number($booked_number);

        if ($create) {
            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => [
                    'number' => $booked_number
                ]
            ];
        }

        return $output;
    }

    public function recycle()
    {
        $internal = [
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;

        $this->load->view('index', ['utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function tb_main()
    {
        $data_get = $this->input->get();
        $get_params = [
            'select' => $this->default_column_select,
            'row_status' => $data_get['row_status'],
            'outputtype' => 'data',
            'order_by' => [
                'column' => (!empty($data_get['order']['0']['column'])) ?  $this->default_column_order[$data_get['order']['0']['column']] : $this->default_order['column'],
                'order' => (!empty($data_get['order']['0']['column'])) ?  $data_get['order']['0']['dir'] : $this->default_order['order']
            ],
            'limit' => [
                'length' => @$data_get['length'],
                'start' => @$data_get['start']
            ],
            'bypass' => false,
            'whereclause' => @$data_get['whereclause']
        ];
        $datas = $this->payment_receipts_model->receipts(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["receipt_number"];  //primary key datas
                $action = '';

                // Declar variable of attributs edit button
                $restore = '';
                $disabled_restore = "disabled";
                $delete_restore = '';

                // Declas variable of attributs edit button
                $delete = '';
                $disabled_delete = "disabled";
                $delete_link = '';

                // Declar variable of attributs edit button
                $edit = $item;
                $disabled_edit = 'disabled text-muted';
                $edit_link = '';

                if ($data_get["row_status"] == 1) { // In mian page
                    # EDIT
                    if (permit_check('FT_' . $this->module_alias . '_EDT', get_user()['id'])) {
                        $disabled_edit = "";
                        $edit_link = base_url() . "$this->module/edit/" . encryptcst($value["id"]);
                    }
                    # Self Update
                    // $edit = "<a title='Detail/Edit rows' href='$edit_link' class='btn-link btn-edit $disabled_edit' data-modalid='#modal-edit' data-formname='form-edit' data-formtype='ajax'>$item</a>";
                    $edit = "<a title='Detail/Edit rows' href='$edit_link' class='btn-link btn-edit $disabled_edit'>$item</a>";

                    # SOFT DELETE
                    if (permit_check('FT_' . $this->module_alias . '_DEL', get_user()['id'])) {
                        $disabled_delete = "";
                        $delete_link = encryptcst($value["id"]);
                    }
                    $delete = "<button title='Delete item - $item' data-id='$delete_link' data-item='$item' class='btn btn-link btn-delete $disabled_delete'><i class='fa-solid fa-trash'></i></button>";
                } elseif ($data_get["row_status"] == 0) { // In recycle page
                    # RESTORE
                    if (permit_check('FT_' . $this->module_alias . '_RST', get_user()['id'])) {
                        $disabled_restore = "";
                    }
                    $restore = "<button title='Restore item - $item' data-id='" . encryptcst($value["id"]) . "' data-item='$item' class='btn btn-link btn-restore $disabled_restore'><i class='fa-solid fa-trash-arrow-up'></i></button>";

                    # PERMANENT DELETE
                    if (permit_check('FT_' . $this->module_alias . '_DEL', get_user()['id'])) {
                        $disabled_delete = "";
                    }
                    $delete = "<button title='Delete item - $item permanently' data-id='" . encryptcst($value["id"]) . "' data-item='$item' class='btn btn-link btn-delete $disabled_delete'><i class='fa-solid fa-trash'></i></button>";
                } else { // Not in main and recycle page
                    $action = "";
                }

                // Marging edit
                $action .= $delete;
                $action .= $restore;

                $row[] = '';
                $row[] = $edit;
                $row[] = $value['invoice_number'];
                $row[] = $value['student_number'];
                $row[] = $value['information'];
                $row[] = ((empty($value['method_name'])) ? 'CASH' : $value['method_name']);
                $row[] = $value['amount'];
                $row[] = date('d F Y', strtotime($value['receipt_date']));
                $row[] = $action;
                $tb_data[] = $row;
            }
        }

        $output = array(
            "firstItem" => encryptcst(@$datas['data']["data"][0]["id"]),
            "draw" => @$data_get['draw'],
            "recordsTotal" => @$datas['data']["all_record"],
            "recordsFiltered" => @$datas['data']["filtered_record"],
            "data" => @$tb_data
        );

        sys_error_logs(@$datas);
        echo json_encode($output);
    }

    public function form_configuration()
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
        $input_get = @$this->input->get();

        if (!empty($input_get['student_number'])) {
            $get_params = [
                'select' => 'payment_receipts.id, receipt_for, payment_receipts.amount, receipt_number, payment_receipts.information',
                'row_status' => 1,
                'outputtype' => 'data',
                'order_by' => [
                    'column' => 'payment_receipts.id',
                    'order' => 'ASC'
                ],
                'limit' => [
                    'length' => -1,
                    'start' => 0
                ],
                'bypass' => true,
                'whereclause' => 'payment_receipts.student_number = ' . $this->db->escape($input_get['student_number'])
            ];
            $data_receipts = $this->payment_receipts_model->receipts(0, $get_params, 'GET');
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape($input_get['student_number']));
            $detailed_invoice = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape($input_get['student_number']));

            if ($data_receipts['status'] && !empty($detailed_payment['data']) && !empty($detailed_invoice['data'])) {
                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'down_payment' => false,
                        'final_payment' => false,
                        'partial_payment' => 0,
                        'total_receipt_amount' => 0,
                        'receipt_options' => '<option value="" selected>--Select--</option>'
                    ]
                ];

                if (strtoupper(@$detailed_invoice['data']['approval_status']) === 'APPROVED') {
                    $down_payment_total = 0;
                    $partial_payment_total = 0;

                    foreach ($data_receipts['data']['data'] as $key => $value) {
                        if (strtoupper($value['receipt_for']) === 'DOWN_PAYMENT') {
                            $down_payment_total += (float) $value['amount'];
                        } elseif (strtoupper($value['receipt_for']) === 'PARTIAL_PAYMENT') {
                            $partial_payment_total += (float) $value['amount'];
                            $output['data']['partial_payment'] += 1;
                        }

                        $output['data']['total_receipt_amount'] += (float) $value['amount'];

                        $output['data']['receipt_options'] .= '<option value="' . $value['receipt_number'] . '">' . $value['information'] . '</option>';
                    }

                    if ($output['data']['total_receipt_amount'] >= (float) $detailed_payment['data']['final_amount']) {
                        $output['data']['final_payment'] = true;
                    } elseif ($down_payment_total >= (float) $detailed_payment['data']['advance_amount']) {
                        $output['data']['down_payment'] = true;
                    }

                    $output['data']['partial_payment'] += 1;
                } else {
                    $output = [
                        'status' => FALSE,
                        'code' => "$this->error_prefix-RRC-E002",
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
                    'code' => "$this->error_prefix-RRC-E001",
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
                'code' => "$this->error_prefix-RRC-E001",
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

        $error_info = get_error_info($output);
        echo json_encode($output);
    }

    public function detailed($id = null)
    {
        $output = null;
        $input_get = @$this->input->get();

        $output = $this->payment_receipts_model->detailed(decryptcst($id), @$input_get['whereclause']);

        if (!empty($output['data'])) {
            $output['data']['outstanding_balance'] = INR($output['data']['outstanding_balance']);
            $output['data']['amount'] = INR($output['data']['amount']);
            $output['data']['remaining_balance'] = INR($output['data']['remaining_balance']);
        }

        echo json_encode($output);
    }

    public function downloads()
    {
        $input_get = $this->input->get();

        $filename = basename($input_get['filename'] ?? '');

        $file = $input_get['file_directory'];

        if (!file_exists($file)) {
            http_response_code(404);
        }

        // Detect MIME type otomatis
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file);
        finfo_close($finfo);

        header("Content-Type: $mime");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Length: " . filesize($file));
        header("Cache-Control: no-cache");

        readfile($file);
        exit;
    }

    public function generate_receipt($receipt_number = null)
    {
        $utilitys = [];
        $input_get = @$this->input->get();
        if (!empty(hex2bin($input_get['receipt_number']))) {
            $receipt_number = hex2bin($input_get['receipt_number']);
            $student_number = hex2bin($input_get['student_number']);

            $utilitys['student'] = $this->students_model->detailed('', 'student_number = ' . $this->db->escape($student_number));
            $utilitys['payment'] = $this->payments_model->detailed('', 'student_number = ' . $this->db->escape($student_number));
            $utilitys['payment_receipt'] = $this->payment_receipts_model->detailed('', 'receipt_number = ' . $this->db->escape($receipt_number));
            $utilitys['payment_method'] = $this->payment_methods_model->detailed(@$utilitys['payment_receipt']['data']['payment_method_id']);
        }

        $this->load->view('payment_receipts/generate_receipts', ['utilitys' => $utilitys]);
    }

    private function create_or_edit_payments($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $data_post = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));
            $data_post = @$data_post['data'];
            if (!empty($datas['advance_amount'])) {
                $data_post['advance_amount'] = $datas['advance_amount'];
            }

            if (!empty($datas['advance_percent'])) {
                $data_post['advance_percent'] = $datas['advance_percent'];
            }

            if (!empty($datas['advance_date'])) {
                $data_post['advance_date'] = $datas['advance_date'];
            }

            if (!empty($datas['final_payment'])) {
                $data_post['final_payment'] = $datas['final_payment'];
            }

            if (!empty($datas['final_payment_date'])) {
                $data_post['final_payment_date'] = $datas['final_payment_date'];
            }

            if ((@$datas['remaining_balance'] === 0) || !empty($datas['remaining_balance'])) {
                $data_post['remaining_balance'] = $datas['remaining_balance'];
            }

            unset($data_post['row_status']);
            unset($data_post['created_by']);
            unset($data_post['updated_by']);
            unset($data_post['created_at']);
            unset($data_post['updated_at']);

            $payment = $this->payments_model->create_and_edit($data_post['id'], $data_post);
            if (@$payment['status']) {
                if (!empty(@$payment['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Create data payment for student number ' . @$data_post['student_number'] . ' successfully.',
                        'level' => 'success',
                    ];
                } elseif (!empty(@$payment['data']['effected_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'UPDATE',
                        'message' => 'Update data payment for student number "' . @$data_post['student_number'] . '" successfully.',
                        'level' => 'success',
                    ];
                } else {
                    $output = get_error_info($payment);
                    $output['status'] = false;
                }
            } elseif (!empty($payment)) {
                $output = get_error_info($payment);
            }
        }

        return $output;
    }

    public function release_receipt()
    {
        $output  = null;
        $input_post = @$this->input->post();

        if (!empty($input_post['student_number'])) {
            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));
            $detailed_invoice = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));

            if (!empty($detailed_student['data'])) {
                $detailed_payment_method = $this->payment_methods_model->detailed($input_post['receipt_method']);
                $receipt_number = $this->generate_number();

                if (((float) $input_post['receipt_amount'] >= (float) $detailed_payment['data']['remaining_balance'])) {
                    $input_post['receipt_for'] = 'final_payment';
                }

                $data_payment = [];
                $information = '';
                if (strtoupper($input_post['receipt_for']) === 'DOWN_PAYMENT') {
                    if ((float) $input_post['receipt_amount'] != (float) $detailed_payment['data']['advance_amount']) {
                        $data_payment['advance_amount'] = (float) $input_post['receipt_amount'];
                        $data_payment['advance_percent'] = ((float) $input_post['receipt_amount'] / (float) $detailed_payment['data']['final_amount']) * 100;
                        $data_payment['remaining_balance'] = (float) $detailed_payment['data']['final_amount'] - $data_payment['advance_amount'];
                        $data_payment['final_payment'] = (float) $data_payment['remaining_balance'];
                    }

                    $information = 'Down Payment';

                    $data_payment['advance_date'] = date('Y-m-d');
                } else {
                    if ((float) $input_post['receipt_amount'] >= (float) $detailed_payment['data']['remaining_balance']) {
                        $information = 'Final Payment';
                        $data_payment['remaining_balance'] = 0;
                        $data_payment['final_payment_date'] = date('Y-m-d', strtotime(@$input_post['receipt_date']));
                    } else {
                        $input_post['receipt_for'] = 'partial_payment';
                        $information = 'Partial Payment - ' . @$input_post['receipt_installment'];
                        $data_payment['remaining_balance'] = (float) $detailed_payment['data']['remaining_balance'] - (float) $input_post['receipt_amount'];
                    }
                }


                $datas = [
                    'student_number' => $input_post['student_number'],
                    'invoice_number' => @$detailed_invoice['data']['invoice_number'],
                    'receipt_number' => @$receipt_number['data']['number'],
                    'payment_method_id' => $input_post['receipt_method'],
                    'method_name' => @$detailed_payment_method['data']['method_name'],
                    'receipt_date' => date('Y-m-d', strtotime(@$input_post['receipt_date'])),
                    'receipt_for' => $input_post['receipt_for'],
                    'information' => $information,
                    'note' => $input_post['receipt_note'],
                    'outstanding_balance' => ((strtoupper($input_post['receipt_for']) === 'DOWN_PAYMENT') ? $detailed_payment['data']['final_amount'] : $detailed_payment['data']['remaining_balance']),
                    'amount' => $input_post['receipt_amount'],
                    'remaining_balance' => ((strtoupper($input_post['receipt_for']) === 'DOWN_PAYMENT') ? $detailed_payment['data']['remaining_balance'] : $data_payment['remaining_balance'])
                ];

                $create = $this->payment_receipts_model->create_and_edit(null, $datas);
                $output['alert'] = get_error_info($create);
                if (@$create['status'] && (!empty(@$create['data']['insert_id']))) {

                    $data_payment['student_number'] = $input_post['student_number'];
                    $payment = $this->create_or_edit_payments($data_payment);
                    $output['toastr'][] = $payment;

                    $output['alert'] = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Create data successfully.',
                        'level' => 'success',
                        'data' => [
                            'receipt_number' => $datas['receipt_number'],
                            'student_number' => $datas['student_number']
                        ]
                    ];
                }
            } else {
                $alert = [
                    'status' => FALSE,
                    'code' => "$this->error_prefix-RRC-E001",
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => ''
                    ],
                    'data' => null

                ];
                $output['alert'] = get_error_info($alert);
            }
        } else {
            $alert = [
                'status' => FALSE,
                'code' => "$this->error_prefix-RRC-E001",
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => ''
                ],
                'data' => null

            ];
            $output['alert'] = get_error_info($alert);
        }

        echo json_encode($output);
    }

    public function create()
    {
        $internal = [
            'create_form' => 'form-create',
            'save_form_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();

        if (!empty($input_post)) {
            $create = $this->payment_receipts_model->create_and_edit(null, $input_post);

            sys_error_logs($create);
            if ($create['status']) {
                $alert  = [
                    'code' => 'CREATE',
                    'message' => 'Create data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => ((!empty($create['data']['insert_id'])) ? base_url() . "$this->module/edit/" . encryptcst($create['data']['insert_id']) : '')
                ];
            }

            if (@$create['status'] && (!empty(@$create['data']['insert_id']))) {
                $alert = [
                    'code' => 'CREATE',
                    'message' => 'Create data successfully.',
                    'level' => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($create['data']['insert_id'])
                ];
            } elseif (!empty($create)) {
                $alert = get_error_info($create);
            }
        }

        $this->load->view('index', ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function edit($id = null)
    {
        $internal = [
            'edit_form' => 'form-edit',
            'save_form_url' => ((permit_check('FT_' . $this->module_alias . '_EDT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        if (!empty($input_post)) {
            $edit = $this->payment_receipts_model->create_and_edit($id, $input_post);

            sys_error_logs($edit);
            if (@$edit['status'] && (!empty(@$edit['data']['effected_id']))) {
                $alert = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($edit['data']['effected_id'])
                ];
            } elseif (!empty($edit)) {
                $alert = get_error_info($edit);
            }
        }

        $detailed = $this->payment_receipts_model->detailed($id);
        if ($detailed['status']) {
            $utilitys['data'] = $detailed['data'];
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        $this->load->view('index', ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->payment_receipts_model->detailed($id);
        $output = $this->payment_receipts_model->receipts($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->payment_receipts_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
