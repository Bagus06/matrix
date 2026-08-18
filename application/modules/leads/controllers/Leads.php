<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Leads extends CI_Controller
{
    protected $module = 'leads';
    protected $module_alias = 'LDS';
    protected $default_column_order = array(null, 'enquiry_number', 'full_name', 'phone', 'email', 'assigned_to_name', 'source_code', 'status', 'updated_at', 'follow_up_date');
    protected $default_order = [
        "column" => "enquiry_number",
        "order" => "ASC"
    ];
    protected $default_column_select = 'leads.*, university_name, short_name, course_name, course_code';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        sync_booked_number('enquiry_number', 'leads');

        $this->load->model('leads_model');
        $this->load->model('lead_contact_logs_model');
        $this->load->model('users/users_model');
        $this->load->model('leads_sources/leads_sources_model');
        $this->load->model('universities/universities_model');
        $this->load->model('university_courses/university_courses_model');
        $this->load->model('students/students_model');
        $this->load->model('payments/payments_model');
        $this->load->model('payment_invoices/payment_invoices_model');

        $this->load->model('geolocation/countries_model');
        $this->load->model('geolocation/states_model');
        $this->load->model('geolocation/cities_model');
        $this->load->model('geolocation/districts_model');
        $this->load->model('geolocation/villages_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->leads_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function followup()
    {
        $this->load->view('index');
    }

    public function followup_count()
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
            'select' => 'leads.id',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'leads.id',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => "leads.status = 'PENDING' AND follow_up_date < '" . date('Y-m-d') . "'"
        ];

        if (user_group_check('GR_MKTST', get_user()['id']) && (strtoupper(get_user()['username']) !== 'DEVELOPER')) {
            $get_params['whereclause'] .= ' AND assigned_to = ' . get_user()['id'];
        }

        $leads_data = $this->leads_model->leads(0, $get_params, 'GET');

        if ($leads_data['status'] === true) {

            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => $leads_data['data']
            ];
        }

        sys_error_logs($leads_data);

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

    public function update_booked_code()
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

    public function is_status_yes()
    {
        $output = null;
        $input_get = @$this->input->get();

        if (!empty($input_get)) {
            $output = $this->payments_model->calculation_fee($input_get);
        }
        echo json_encode($output);
    }

    private function create_contact_log_from_form($lead_id = null, $input_post = null, $contact_context = null)
    {
        $output = null;

        if (empty($input_post['record_call'])) {
            return $output;
        }

        $required_permission = $contact_context === 'NEW_LEAD' ? 'FT_LDS_CRT' : 'FT_LDS_EDT';
        if (!permit_check($required_permission, get_user()['id'])) {
            $output = [
                'status' => FALSE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'message' => 'Lead was saved, but you do not have permission to record the call.',
                'data' => null
            ];

            return $output;
        }

        $contact_result = strtoupper(trim((string) @$input_post['contact_result']));
        if (!in_array($contact_result, ['RESPONDED', 'NO_RESPONSE'])) {
            $output = [
                'status' => FALSE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'message' => 'Lead was saved, but the call log was not saved because the call result is invalid.',
                'data' => null
            ];

            return $output;
        }

        $lead = $this->leads_model->detailed((int) $lead_id);
        $user = get_user();
        $can_access_all = strtoupper($user['username']) === 'DEVELOPER'
            || user_group_check('GR_ADMIN', $user['id']);
        if (empty($lead['status']) || empty($lead['data']) || (int) $lead['data']['row_status'] !== 1
            || (!$can_access_all && (int) $lead['data']['assigned_to'] !== (int) $user['id'])) {
            $output = [
                'status' => FALSE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'message' => 'Lead was saved, but the call log could not be linked to your account.',
                'data' => null
            ];

            return $output;
        }

        $datas = [
            'lead_id' => (int) $lead_id,
            'contact_context' => $contact_context,
            'contact_result' => $contact_result,
            'note' => substr(trim((string) @$input_post['contact_note']), 0, 255)
        ];
        $output = $this->lead_contact_logs_model->create_and_edit(null, $datas);

        return $output;
    }

    private function append_contact_log_result(&$alert, $contact_log = null)
    {
        if ($contact_log === null) {
            return;
        }

        sys_error_logs($contact_log);
        if (!empty($contact_log['status'])) {
            $alert['message'] .= ' Call log saved successfully.';
            return;
        }

        $message = !empty($contact_log['message'])
            ? $contact_log['message']
            : 'Call log failed to save.';
        $alert['message'] .= ' ' . $message;
        $alert['level'] = 'warning';
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
            'whereclause' => @$data_get['whereclause'],
            'real_page' => $data_get['real_page']
        ];
        $datas = $this->leads_model->leads(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["enquiry_number"];  //primary key datas
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
                $row[] = $value['assigned_to_name'];
                $row[] = $value['source_code'];
                $row[] = $value['status'];
                $row[] = date('d F Y', strtotime($value['updated_at']));
                $row[] = date('d F Y', strtotime($value['follow_up_date']));
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

        $output = $this->leads_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    private function create_or_edit_student($datas = null)
    {
        $output = null;
        if (!empty($datas)) {
            $detailed_student = $this->students_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));

            $fees = $this->payments_model->calculation_fee([
                'course_id' => @$datas['course_id'],
                'source_code' => @$datas['source_code'],
                'tax_percent' => @$datas['tax_percent'],
                'advance_percent' => @$datas['advance_percent'],
                'additional_certificate_fee' => @$datas['additional_certificate_fee'],
            ]);
            if (!empty($fees['data'])) {
                $datas['final_fees'] = $fees['data']['final_amount'];
            }

            if (!empty($detailed_student['data']['id'])) {
                $student = $this->students_model->create_and_edit($detailed_student['data']['id'], $datas);
            } else {
                $student = $this->students_model->create_and_edit(0, $datas);
            }

            if (@$student['status']) {
                $update_booked_number = update_booked_number(1, $datas['student_number']);
                if (!empty(@$student['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Create data student number ' . @$datas['student_number'] . ' successfully.',
                        'level' => 'success',
                    ];
                } elseif (!empty(@$student['data']['effected_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'UPDATE',
                        'message' => 'Update data student number "' . @$datas['student_number'] . '" successfully.',
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
                    $output['status'] = false;
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
            $detailed_payment = $this->payment_invoices_model->detailed(0, 'student_number = ' . $this->db->escape(@$datas['student_number']));

            $fees = $this->payments_model->calculation_fee([
                'course_id' => @$datas['course_id'],
                'source_code' => @$datas['source_code'],
                'tax_percent' => @$datas['tax_percent'],
                'advance_percent' => @$datas['advance_percent'],
                'additional_certificate_fee' => @$datas['additional_certificate_fee'],
            ]);

            if (!empty($fees['data'])) {
                $datas['amount'] = $fees['data']['final_amount'] - ($fees['data']['final_amount'] * ((float) @$datas['tax_percent'] / 100));
                $datas['tax_percent'] = @$datas['tax_percent'];
                $datas['final_amount'] = $fees['data']['final_amount'];
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
                $update_booked_number = update_booked_number(1, $datas['invoice_number']);
                if (!empty(@$invoices['data']['insert_id'])) {
                    $output = [
                        'status' => true,
                        'code' => 'CREATE',
                        'message' => 'Invoice for student number "' . @$datas['student_number'] . '" successfully created and submitted to admin.',
                        'level' => 'success',
                    ];
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
            $utilitys['call_log_input'] = $input_post;
        }

        $utilitys['religion'] = $this->leads_model->religion();
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

        $utilitys['priority'] = $this->leads_model->priority();
        $utilitys['status'] = $this->leads_model->status();

        if (!empty($input_post)) {
            $create = $this->leads_model->create_and_edit(null, $input_post);

            sys_error_logs($create);
            if (@$create['status'] && (!empty(@$create['data']['insert_id']))) {
                $update_booked_number = update_booked_number(1, $input_post['enquiry_number']);
                $alert = [
                    'code' => 'CREATE',
                    'message' => 'Add data leads successfully.',
                    'level' => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($create['data']['insert_id'])
                ];

                $contact_log = $this->create_contact_log_from_form(
                    $create['data']['insert_id'],
                    $input_post,
                    'NEW_LEAD'
                );
                $this->append_contact_log_result($alert, $contact_log);

                if (@$input_post['status'] === 'YES') {
                    $student = $this->create_or_edit_student($input_post);
                    $toastr[] = $student;

                    if ($student['status']) {
                        $payments = $this->create_or_edit_payments($input_post);
                        $toastr[] = $payments;

                        if ($payments['status']) {
                            $invoices = $this->create_or_edit_invoices($input_post);
                            $toastr[] = $invoices;
                        }
                    }
                }
            } elseif (!empty($create)) {
                # Set string geolocation
                if (!empty($input_post['country_id'])) {
                    $country = $this->countries_model->detailed($input_post['country_id']);
                    if (!empty($country['data']['name'])) {
                        $input_post['country'] = $country['data']['name'];
                    }
                } else {
                    unset($input_post['country_id']);
                }
                if (!empty($input_post['state_id'])) {
                    $state = $this->states_model->detailed($input_post['state_id']);
                    if (!empty($state['data']['name'])) {
                        $input_post['state'] = $state['data']['name'];
                    }
                } else {
                    unset($input_post['state_id']);
                }
                if (!empty($input_post['city_id'])) {
                    $city = $this->cities_model->detailed($input_post['city_id']);
                    if (!empty($city['data']['name'])) {
                        $input_post['city'] = $city['data']['name'];
                    }
                } else {
                    unset($input_post['city_id']);
                }
                if (!empty($input_post['district_id'])) {
                    $district = $this->districts_model->detailed($input_post['district_id']);
                    if (!empty($district['data']['name'])) {
                        $input_post['district'] = $district['data']['name'];
                    }
                } else {
                    unset($input_post['district_id']);
                }

                $utilitys['data'] = $input_post;
                $alert = get_error_info($create);
            }
        } else {
            $utilitys['data']['follow_up_date'] = date('Y-m-d', strtotime('+1 days'));
            $utilitys['data']['assigned_to'] = get_user()['id'];
            $utilitys['data']['enquiry_number'] = last_booked_number('ENQ-' . date('ymd') . '-', 3);
            create_booked_number($utilitys['data']['enquiry_number']);
        }

        $this->load->view('index', ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function edit($id = null)
    {
        $internal = [
            'create_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'edit_form' => 'form-edit',
            'save_form_url' => ((permit_check('FT_' . $this->module_alias . '_EDT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $toastr = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        if (!empty($input_post)) {
            $utilitys['call_log_input'] = $input_post;
        }

        $utilitys['religion'] = $this->leads_model->religion();
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

        $utilitys['priority'] = $this->leads_model->priority();
        $utilitys['status'] = $this->leads_model->status();

        if (!empty($input_post)) {
            $edit = $this->leads_model->create_and_edit($id, $input_post);

            sys_error_logs($edit);
            $lead_update_accepted = @$edit['status'] && (
                !empty(@$edit['data']['effected_id'])
                || (!empty($input_post['record_call']) && @$edit['code'] === 'LEAD-PTC-E001')
            );

            if ($lead_update_accepted) {
                $alert = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($id)
                ];

                $contact_log = $this->create_contact_log_from_form($id, $input_post, 'FOLLOW_UP');
                $this->append_contact_log_result($alert, $contact_log);
            } elseif (!empty($edit)) {
                $alert = get_error_info($edit);
            }

            if (@$input_post['status'] === 'YES') {
                $student = $this->create_or_edit_student($input_post);
                $toastr[] = $student;

                if ($student['status']) {
                    $payments = $this->create_or_edit_payments($input_post);
                    $toastr[] = $payments;

                    if ($payments['status']) {
                        $invoices = $this->create_or_edit_invoices($input_post);
                        $toastr[] = $invoices;
                    }
                }
            }
        }

        $detailed = $this->leads_model->detailed($id);
        if ($detailed['status']) {
            $utilitys['data'] = $detailed['data'];

            $detailed_student = $this->students_model->detailed(0, 'enquiry_number = ' . $this->db->escape($detailed['data']['enquiry_number']));
            if ($detailed_student['status']) {
                $utilitys['data_student'] = $detailed_student['data'];
            } else {
                sys_error_logs($detailed_student);
            }

            $detailed_payment = $this->payments_model->detailed(0, 'student_number = ' . $this->db->escape(@$detailed_student['data']['student_number']));
            if ($detailed_payment['status']) {
                $utilitys['data_payment'] = $detailed_payment['data'];
            } else {
                sys_error_logs($detailed_student);
            }

            $detailed_invoice = $this->payment_invoices_model->detailed(0, 'invoice_number = ' . $this->db->escape(@$detailed_payment['data']['invoice_number']));
            if ($detailed_invoice['status']) {
                $utilitys['data_invoice'] = $detailed_invoice['data'];
            } else {
                sys_error_logs($detailed_student);
            }
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        $this->load->view('index', ['alert' => $alert, 'toastr' => $toastr, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->leads_model->detailed($id);
        $output = $this->leads_model->leads($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->leads_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
