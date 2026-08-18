<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Students extends CI_Controller
{
    protected $module = 'students';
    protected $module_alias = 'STD';
    protected $default_column_order = array(null, 'student_number', 'full_name', 'phone', 'email', 'university_name', 'course_name', null, null, 'leads.assigned_to_name', 'students.created_at');
    protected $default_order = [
        "column" => "student_number",
        "order" => "ASC"
    ];
    protected $default_column_select = 'students.*, university_name, short_name, course_name, course_code, leads.assigned_to_name';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        sync_booked_number('student_number', 'students');

        $this->load->model('students_model');

        $this->load->model('universities/universities_model');
        $this->load->model('university_courses/university_courses_model');
        $this->load->model('payments/payments_model');
        $this->load->model('payment_invoices/payment_invoices_model');
        $this->load->model('payment_methods/payment_methods_model');
        $this->load->model('leads/leads_model');

        $this->load->model('geolocation/countries_model');
        $this->load->model('geolocation/states_model');
        $this->load->model('geolocation/cities_model');
        $this->load->model('geolocation/districts_model');
        $this->load->model('geolocation/villages_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->students_model->query_builder($data_get);

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
        $prefix = date('my');
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
        $datas = $this->students_model->students(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["student_number"];  //primary key datas
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
                $row[] = $value['full_name'];
                $row[] = $value['phone'];
                $row[] = $value['email'];
                $row[] = $value['university_name'] . ((!empty($value['short_name'])) ? ' ( ' . $value['short_name'] . ' )' : '');
                $row[] = $value['course_name'] . ((!empty($value['course_code'])) ? ' ( ' . $value['course_code'] . ' )' : '');
                $row[] = $value['city'] . ', ' . $value['state'] . ', ' . $value['country'];
                $row[] = ((!empty($value['additional_certificate']) && hasDecimalValue($value['additional_certificate_fee'])) ? $value['additional_certificate'] . ' ( ' . $value['additional_certificate_fee'] . ' )' : 'None');
                $row[] = $value['assigned_to_name'];
                $row[] = date('d F Y', strtotime($value['created_at']));
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

    public function downloads()
    {
        $input_get = $this->input->get();

        $filename = basename($input_get['filename'] ?? '');

        $file = $input_get['file_directory'] . $input_get['filename'];

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

    public function university_report()
    {
        $input_get = @$this->input->get();

        $output = $this->students_model->university_report($input_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function detailed($id = null)
    {
        $output = null;
        $input_get = @$this->input->get();

        $output = $this->students_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    private function create_or_edit_payments($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));
            $detailed_payment_invoice = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));

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
                $datas['discount'] = @$fees['data']['discount'];
                $datas['tax_percent'] = @$datas['tax_percent'];
                $datas['final_amount'] = $fees['data']['final_amount'];
                $datas['advance_percent'] = @$datas['advance_percent'];
                $datas['advance_amount'] = $fees['data']['advance_amount'];
                $datas['remaining_balance'] = ((strtoupper(@$detailed_payment_invoice['data']['approval_status']) === 'APPROVED') ? $fees['data']['remaining_balance'] : @$detailed_payment['data']['remaining_balance']);
                $datas['final_payment'] = (float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $fees['data']['final_payment'])) ? $amount : 0;
            }

            if (!empty($detailed_payment['data']['id'])) {
                $payment = $this->payments_model->create_and_edit($detailed_payment['data']['id'], $datas);
            } else {
                $datas['status'] = 'UNPAID';
                $payment = $this->payments_model->create_and_edit(0, $datas);
            }

            if (@$payment['status']) {
                if (!empty(@$payment['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Create data payment for student number ' . @$datas['student_number'] . ' successfully.',
                        'level' => 'success',
                    ];
                } elseif (!empty(@$payment['data']['effected_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'UPDATE',
                        'message' => 'Update data payment for student number "' . @$datas['student_number'] . '" successfully.',
                        'level' => 'success',
                    ];
                } else {
                    $output = get_error_info($payment);
                    $output['status'] = true;
                }
            } elseif (!empty($payment)) {
                $output = get_error_info($payment);
            }
        }

        return $output;
    }

    private function create_or_edit_invoices($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));
            $detailed_payment_invoice = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));
            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape($datas['student_number']));

            if (!empty($detailed_student['data'])) {
                $datas['information'] = 'Fee for studying at ' . @$detailed_student['data']['university_name'] . ' (' . @$detailed_student['data']['short_name'] . ')' . ((!empty(@$detailed_student['data']['city'])) ? ', ' . @$detailed_student['data']['city'] : '') . ' for ' . @$detailed_student['data']['course_name'] . ' (' . @$detailed_student['data']['course_code'] . ') Course';
            } else {
                $datas['information'] = 'Fee for studying.';
            }

            if (!empty($detailed_payment_invoice['data']['id'])) {
                $datas['amount'] = $detailed_payment['data']['final_amount'] - ($detailed_payment['data']['final_amount'] * ((float) @$detailed_payment['data']['tax_percent'] / 100));
                $datas['tax_percent'] = @$detailed_payment['data']['tax_percent'];
                $datas['final_amount'] = $detailed_payment['data']['final_amount'];
                $datas['request_date'] = date('Y-m-d');

                $invoices = $this->payment_invoices_model->create_and_edit($detailed_payment_invoice['data']['id'], $datas);
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
                    $output['status'] = true;
                }
            } elseif (!empty($invoices)) {
                $output = get_error_info($invoices);
            }
        }

        return $output;
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
            $create = $this->students_model->create_and_edit(null, $input_post);

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

        $utilitys['religion'] = $this->students_model->religion();
        $data_get_universities = [
            'select' => 'id, university_name, short_name',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'university_name',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ""
        ];
        $utilitys['universities'] = $this->universities_model->universities(0, $data_get_universities, 'GET');

        $data_get_countries = [
            'select' => 'id, name, iso2',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'name',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => "iso2 = 'IN'"
        ];
        $utilitys['countries'] = $this->countries_model->countries(0, $data_get_countries, 'GET');

        $data_get_assigned = [
            'select' => 'user_id, name',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'name',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => "JSON_CONTAINS(permission_group, '\"GR_MKTST\"') AND username != 'DEVELOPER'"
        ];
        $utilitys['assigned'] = $this->users_model->users(0, $data_get_assigned, 'GET');

        $data_get_leads_sources = [
            'select' => 'id, source_code, source_name, account, b2b_company_name, ref_name',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'source_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ""
        ];
        $utilitys['leads_sources'] = $this->leads_sources_model->leads_sources(0, $data_get_leads_sources, 'GET');

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

        $utilitys['priority'] = $this->leads_model->priority();
        $utilitys['status'] = $this->leads_model->status();

        if (!empty($input_post)) {
            $edit = $this->students_model->create_and_edit($id, $input_post);

            sys_error_logs($edit);
            if (@$edit['status'] && (!empty(@$edit['data']['effected_id']))) {
                $alert = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($edit['data']['effected_id'])
                ];

                if ($edit['status'] && !empty($input_post['course_id'])) {
                    $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape($input_post['student_number']));

                    $input_post['invoice_number'] = @$detailed_payment['data']['invoice_number'];
                    $payments = $this->create_or_edit_payments($input_post);
                    $toastr[] = $payments;

                    if ($payments['status']) {
                        $invoices = $this->create_or_edit_invoices($input_post);
                        $toastr[] = $invoices;
                    }
                }
            } elseif (!empty($edit)) {
                $alert = get_error_info($edit);
            }
        }

        $detailed = $this->students_model->detailed($id);
        if ($detailed['status']) {
            $utilitys['data'] = $detailed['data'];

            $utilitys['data_leads'] = $this->leads_model->detailed(0, 'enquiry_number = ' . $this->db->escape(@$detailed['data']['enquiry_number']));
            $utilitys['data_payment'] = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$detailed['data']['student_number']));
            $utilitys['data_invoice'] = $this->payment_invoices_model->detailed(0, 'invoice_number = ' . $this->db->escape(@$utilitys['data_payment']['data']['invoice_number']));
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        $this->load->view('index', ['alert' => $alert, 'toastr' => @$toastr, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->students_model->detailed($id);
        $output = $this->students_model->students($id, @$detailed['data']['row_status'], 'DELETE');

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->students_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
