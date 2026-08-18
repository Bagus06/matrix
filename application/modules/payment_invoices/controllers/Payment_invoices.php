<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\DocProtect;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Element\TextRun;

class Payment_invoices extends CI_Controller
{
    protected $module = 'payment_invoices';
    protected $module_alias = 'INV';
    protected $default_column_order = array(null, 'test', 'created_at');
    protected $default_order = [
        "column" => "test",
        "order" => "ASC"
    ];
    protected $default_column_select = 'id, test, created_at';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        sync_booked_number('invoice_number', 'payment_invoices');

        $this->load->model('payment_invoices_model');
        $this->load->model('students/students_model');
        $this->load->model('leads/leads_model');
        $this->load->model('payments/payments_model');
        $this->load->model('payment_methods/payment_methods_model');
        $this->load->model('payment_invoices/payment_invoices_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->payment_invoices_model->query_builder($data_get);

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
        $prefix = '#INV-' . date('Y');
        $student_number = last_booked_number($prefix, 4);

        $create = create_booked_number($student_number);

        if ($create) {
            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => [
                    'number' => $student_number
                ]
            ];
        }

        echo json_encode($output);
    }

    public function update_booked_number()
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

        if (!empty($input_get['number'])) {
            $update = update_booked_number(0, $input_get['number']);

            if ($update) {
                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'number' => $input_get['number']
                    ]
                ];
            }
        }

        sys_error_logs($output);

        echo json_encode($output);
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
        $datas = $this->payment_invoices_model->invoices(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["test"];  //primary key datas
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
                $row[] = $value['created_at'];
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

    public function detailed($id = null)
    {
        $output = null;
        $input_get = @$this->input->get();

        $output = $this->payment_invoices_model->detailed(decryptcst($id), @$input_get['whereclause']);

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

    /*
    public function generate_invoice()
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
            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape(@$input_get['student_number']));
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$input_get['student_number']));
            $detailed_invoice = $this->payment_invoices_model->detailed(0, 'invoice_number = ' . $this->db->escape(@$detailed_payment['data']['invoice_number']));
            $get_params = [
                'select' => '*',
                'row_status' => 1,
                'outputtype' => 'data',
                'order_by' => [
                    'column' => 'method_name',
                    'order' => 'ASC'
                ],
                'limit' => [
                    'length' => -0,
                    'start' => 0
                ],
                'bypass' => false,
                'whereclause' => ''
            ];
            $data_payment_methods = $this->payment_methods_model->payment_methods(0, $get_params, 'GET');

            if (!empty($detailed_student['data']) && !empty($detailed_payment['data'])) {
                $base_path = './assets/modules/payment_invoices/documents';
                $docx_path = "$base_path/docx/";
                $pdf_path = "$base_path/pdf/";
                if (!file_exists($docx_path)) {
                    mkdir($docx_path, 0777, true);
                }
                if (!file_exists($pdf_path)) {
                    mkdir($pdf_path, 0777, true);
                }

                $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($base_path . '/template/invoice_template.docx');

                $full_address = @$detailed_student['data']['country'] . ', ' . @$detailed_student['data']['state'] . ((!empty(@$detailed_student['data']['city'])) ? ', ' . @$detailed_student['data']['city'] : '') . ((!empty(@$detailed_student['data']['district'])) ? ', ' . @$detailed_student['data']['district'] : '')  . ((!empty(@$detailed_student['data']['address'])) ? ', ' . @$detailed_student['data']['address'] : '') . ((!empty(@$detailed_student['data']['postal_code'])) ? ' - ' . @$detailed_student['data']['postal_code'] : '');
                $full_university_name = @$detailed_student['data']['university_name'] . ((!empty(@$detailed_student['data']['short_name'])) ? ' (' . @$detailed_student['data']['short_name'] . ')' : '');
                $full_course_name = @$detailed_student['data']['course_name'] . ((!empty(@$detailed_student['data']['course_code'])) ? ' (' . @$detailed_student['data']['course_code'] . ')' : '');

                $textRun = new TextRun();
                if (!empty($data_payment_methods['data']['data'])) {
                    foreach ($data_payment_methods['data']['data'] as $key => $value) {
                        $textRun->addText('- ');
                        $textRun->addText(@$value['category'] . ' : ', ['bold' => true]);
                        $textRun->addText(@$value['account_identifier'] . ' (' . @$value['method_name'] . ') - ' . @$value['account_name']);
                        $textRun->addTextBreak();
                    }

                    $textRun->addText('- ');
                    $textRun->addText('Cash : ', ['bold' => true]);
                    $textRun->addText(' Visit Office ');
                    $textRun->addText('MODWAY International Academy', ['bold' => true]);
                    $phpWord->setComplexValue('payment_methods', $textRun);
                }

                $phpWord->setValues([
                    'invoice_number' => @$detailed_payment['data']['invoice_number'],
                    'invoice_date' => ((!empty(@$detailed_invoice['data']['invoice_date'])) ? date('F d, Y', strtotime(@$detailed_invoice['data']['invoice_date'])) : ''),
                    'full_name' => @$detailed_student['data']['full_name'],
                    'full_address' => $full_address,
                    'phone' => @$detailed_student['data']['phone'],
                    'email' => @$detailed_student['data']['email'],
                    'student_number' => @$detailed_student['data']['student_number'],
                    'full_university_name' => $full_university_name,
                    'full_course_name' => htmlspecialchars($full_course_name, ENT_XML1 | ENT_COMPAT, 'UTF-8'),
                    'information' => htmlspecialchars(@$detailed_invoice['data']['information'], ENT_XML1 | ENT_COMPAT, 'UTF-8'),
                    'total_amount' => @$detailed_payment['data']['total_amount'],
                    'tax_percent' => @$detailed_payment['data']['tax_percent'] . '%',
                    'final_amount' => @$detailed_payment['data']['final_amount'],
                    'discount_percent' => @$detailed_payment['data']['discount_percent'] . '%',
                    'advance_percent' => @$detailed_payment['data']['advance_percent'],
                    'advance_amount' => @$detailed_payment['data']['advance_amount'],
                    'due_date' => ((!empty(@$detailed_payment['data']['due_date'])) ? date('F d, Y', strtotime(@$detailed_payment['data']['due_date'])) : '')
                ]);

                $filename = @$detailed_invoice['data']['invoice_number'] . '.docx';
                $phpWord->saveAs($docx_path . $filename);

                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'filename' => $filename,
                        'file_directory' => $docx_path . $filename,
                    ]
                ];
            } else {
                $output = [
                    'status' => FALSE,
                    'code' => "INVC-RIV-E001",
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Data does not exist in the database.'
                    ],
                    'data' => null
                ];
            }
        } else {
            $output = [
                'status' => FALSE,
                'code' => "INVC-RIV-E001",
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => [
                    'file' => __FILE__,
                    'line' => __LINE__,
                    'hint' => 'Empty student number post data'
                ],
                'data' => null
            ];
        }

        sys_error_logs($output);
        echo json_encode($output);
    }
    */

    public function generate_invoice($student_number = null)
    {
        $utilitys = [];
        $data_get = @$this->input->get();
        if (!empty(hex2bin($data_get['student_number']))) {
            $student_number = hex2bin($data_get['student_number']);
            $utilitys['student_data'] = $this->students_model->detailed('', "student_number = '" . $student_number . "'");
            $utilitys['payment'] = $this->payments_model->detailed('', "student_number = '" . $student_number . "'");
            $utilitys['peyment_invoice'] = $this->payment_invoices_model->detailed('', "invoice_number = '" . @$utilitys['payment']['data']['invoice_number'] . "'");

            $get_params = [
                'select' => '*',
                'row_status' => 1,
                'outputtype' => 'data',
                'order_by' => [
                    'column' => 'account_name',
                    'order' => 'ASC'
                ],
                'limit' => [
                    'length' => -1,
                    'start' => 0
                ],
                'bypass' => true,
                'whereclause' => ''
            ];
            $payment_methods = $this->payment_methods_model->payment_methods('', $get_params, 'GET');

            if (!empty($payment_methods['data']['data'])) {
                $utilitys['payment_methods'] = [];
                foreach ($payment_methods['data']['data'] as $key => $value) {
                    $utilitys['payment_methods'][$value['method_code']] = $value;
                }
            }
        }

        $this->load->view('payment_invoices/generate_invoice', ['utilitys' => $utilitys]);
    }

    private function create_or_edit_payments($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));

            $fees = $this->payments_model->calculation_fee([
                'course_id' => @$datas['course_id'],
                'source_code' => @$datas['source_code'],
                'tax_percent' => @$datas['tax_percent'],
                'advance_percent' => @$datas['advance_percent'],
                'aditional_discount' => @$datas['aditional_discount'],
                'additional_certificate_fee' => @$datas['additional_certificate_fee'],
            ]);

            if (!empty($fees['data'])) {
                $datas['total_amount'] = $fees['data']['total_amount'];
                $datas['additional_certificate_fee'] = $datas['additional_certificate_fee'];
                $datas['discount'] = @$fees['data']['discount'];
                $datas['aditional_discount'] = @$fees['data']['aditional_discount'];
                $datas['total_discount'] = @$fees['data']['total_discount'];
                $datas['tax_percent'] = @$datas['tax_percent'];
                $datas['final_amount'] = $fees['data']['final_amount'];
                $datas['advance_percent'] = @$datas['advance_percent'];
                $datas['advance_amount'] = $fees['data']['advance_amount'];
                $datas['remaining_balance'] = $fees['data']['remaining_balance'];
                $datas['final_payment'] = (float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $fees['data']['final_payment'])) ? $amount : 0;
            }

            if (!empty($detailed_payment['data']['id'])) {
                $student = $this->payments_model->create_and_edit($detailed_payment['data']['id'], $datas);
            } else {
                $datas['status'] = 'UNPAID';
                $student = $this->payments_model->create_and_edit(0, $datas);
            }

            if (@$student['status']) {
                if (!empty(@$student['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Create data payment for student number ' . @$datas['student_number'] . ' successfully.',
                        'level' => 'success',
                    ];
                } elseif (!empty(@$student['data']['effected_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'UPDATE',
                        'message' => 'Update data payment for student number "' . @$datas['student_number'] . '" successfully.',
                        'level' => 'success',
                    ];
                } else {
                    $output = get_error_info($student);
                    $output['status'] = false;
                }
            } elseif (!empty($student)) {
                $output = get_error_info($student);
            }
        }

        return $output;
    }

    private function create_or_edit_invoices($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $detailed_payment = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));

            $fees = $this->payments_model->calculation_fee([
                'course_id' => @$datas['course_id'],
                'source_code' => @$datas['source_code'],
                'tax_percent' => @$datas['tax_percent'],
                'advance_percent' => @$datas['advance_percent'],
                'aditional_discount' => @$datas['aditional_discount'],
                'additional_certificate_fee' => @$datas['additional_certificate_fee'],
            ]);
            if (!empty($fees['data'])) {
                $datas['amount'] = $detailed_payment['data']['final_amount'] - ($detailed_payment['data']['final_amount'] * ((float) @$detailed_payment['data']['tax_percent'] / 100));
                $datas['tax_percent'] = @$detailed_payment['data']['tax_percent'];
                $datas['final_amount'] = $detailed_payment['data']['final_amount'];
            }

            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape($datas['student_number']));
            if (!empty($detailed_student['data'])) {
                $datas['information'] = 'Fee for studying at ' . @$detailed_student['data']['university_name'] . ' (' . @$detailed_student['data']['short_name'] . ')' . ((!empty(@$detailed_student['data']['city'])) ? ', ' . @$detailed_student['data']['city'] : '') . ' for ' . @$detailed_student['data']['course_name'] . ' (' . @$detailed_student['data']['course_code'] . ') Course';
            } else {
                $datas['information'] = 'Fee for studying.';
            }

            if (!empty($detailed_payment['data']['id'])) {
                $datas['request_date'] = date('Y-m-d');

                $invoices = $this->payment_invoices_model->create_and_edit($detailed_payment['data']['id'], $datas);
            } else {
                $datas['approval_status'] = 'WAITING';
                $datas['sending_status'] = 0;
                $datas['request_date'] = date('Y-m-d');
                $datas['request_by'] = $datas['assigned_to'];

                $invoices = $this->payment_invoices_model->create_and_edit(0, $datas);
            }

            if (@$invoices['status']) {
                if (!empty(@$invoices['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Invoice for student number "' . @$datas['student_number'] . '" successfully created and submitted to admin.',
                        'level' => 'success',
                    ];

                    update_booked_number(1, $datas['invoice_number']);
                } elseif (!empty(@$invoices['data']['effected_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'UPDATE',
                        'message' => 'Update data invoices for student number "' . @$datas['student_number'] . '" successfully.',
                        'level' => 'success',
                    ];
                } else {
                    $output = get_error_info($invoices);
                    $output['status'] = false;
                }
            } elseif (!empty($invoices)) {
                $output = get_error_info($invoices);
            }
        }

        return $output;
    }

    public function release_invoice()
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

        if (!empty($input_post)) {
            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));
            $detailed_leads = $this->leads_model->detailed(0, 'enquiry_number = ' . $this->db->escape(@$detailed_student['data']['enquiry_number']));
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));

            if (!empty($detailed_leads['data'])) {
                $datas = [
                    'student_number' => $input_post['student_number'],
                    'invoice_number' => @$detailed_payment['data']['invoice_number'],
                    'tax_percent' => @$input_post['tax_percent'],
                    'advance_percent' => @$input_post['advance_percent'],
                    'discount' => @$input_post['discount'],
                    'aditional_discount' => @$input_post['aditional_discount'],
                    'additional_certificate_fee' => @$input_post['additional_certificate_fee'],
                    'course_id' => @$detailed_student['data']['course_id'],
                    'source_code' => @$detailed_leads['data']['source_code'],
                    'assigned_to' => @$input_post['assigned_to'],
                    'approval_by' => get_user()['id'],
                    'approval_date' => date('Y-m-d'),
                    'invoice_date' => date('Y-m-d'),
                    'approval_status' => 'APPROVED'
                ];

                $invoice = $this->create_or_edit_invoices($datas);
                $toastr[] = $invoice;

                $datas['due_date'] = $input_post['due_date'];

                $payment = $this->create_or_edit_payments($datas);
                $toastr[] = $payment;

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
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => [
                        'file' => __FILE__,
                        'line' => __LINE__,
                        'hint' => 'Inquiry or student data not found.'
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
                    'hint' => 'Post data is empty.'
                ],
                'data' => null
            ];
        }

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function change_payment()
    {
        $output = null;
        $input_get = @$this->input->get();

        if (!empty($input_get)) {
            $output = $this->payments_model->calculation_fee($input_get);
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
            $create = $this->payment_invoices_model->create_and_edit(null, $input_post);

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
            $edit = $this->payment_invoices_model->create_and_edit($id, $input_post);

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

        $detailed = $this->payment_invoices_model->detailed($id);
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
        $detailed = $this->payment_invoices_model->detailed($id);
        $output = $this->payment_invoices_model->invoices($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->payment_invoices_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
