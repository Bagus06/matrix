<?php defined('BASEPATH') or exit('No direct script access allowed');
class University_courses extends CI_Controller
{
    protected $module = 'university_courses';
    protected $module_alias = 'UCS';
    protected $default_column_order = array(null, 'course_name', 'course_code', 'universities.university_name', 'course_level', 'course_type', 'duration_year', 'total_semesters', 'eligibility', 'description', 'status');
    protected $default_order = [
        "column" => "universities.university_name",
        "order" => "ASC"
    ];
    protected $default_column_select = 'university_courses.*, universities.university_name';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('university_courses_model');
        $this->load->model('universities/universities_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->university_courses_model->query_builder($data_get);

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

    public function option_courses()
    {
        $utilitys = [];
        $input_get = @$this->input->get();

        if (!empty($input_get['university_id'])) {
            $get_params = [
                'select' => 'university_courses.id, course_name, course_code',
                'row_status' => 1,
                'outputtype' => 'data',
                'order_by' => [
                    'column' => 'course_name',
                    'order' => 'ASC'
                ],
                'limit' => [
                    'length' => -1,
                    'start' => 0
                ],
                'bypass' => false,
                'whereclause' => 'university_id = ' . $this->db->escape(@$input_get['university_id'])
            ];
            $utilitys['courses'] = $this->university_courses_model->courses(0, $get_params, 'GET');

            sys_error_logs($utilitys['courses']);
        }

        $this->load->view('option-courses', ['utilitys' => $utilitys, 'course_id' => @$input_get['course_id']]);
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
        $datas = $this->university_courses_model->courses(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["course_name"];  //primary key datas
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
                $row[] = $value['course_code'];
                $row[] = $value['university_name'];
                $row[] = $value['course_level'];
                $row[] = $value['course_type'];
                $row[] = $value['duration_year'];
                $row[] = $value['total_semesters'];
                $row[] = $value['eligibility'];
                $row[] = $value['description'];
                $row[] = $value['status'];
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

        $output = $this->university_courses_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function count_final_fee()
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

        if (!empty($input_get['markup'])) {
            $input_get['dry_fee'] = (float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $input_get['dry_fee'])) ? $amount : 0;
            $input_get['markup'] = (int) $input_get['markup'];
            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => [
                    'fee' => $input_get['dry_fee'],
                    'markup_fee_percent' => $input_get['markup'],
                    'final_fee' => number_format((float) $input_get['dry_fee'] + ($input_get['dry_fee'] * $input_get['markup'] / 100), 2)
                ]
            ];
        } else {
            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => [
                    'fee' => $input_get['dry_fee'],
                    'markup_fee_percent' => $input_get['markup'],
                    'final_fee' => number_format((float) preg_match('/^\d+(\.\d{1,2})?$/', $amount = preg_replace('/[^0-9.]/', '', $input_get['dry_fee'])) ? $amount : 0, 2)
                ]
            ];
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

        $utilitys['course_level'] = ['Diploma', 'UG', 'PG', 'Doctorate'];
        $utilitys['course_type'] = ['Full Time', 'Part Time', 'Distance', 'Online'];
        $get_params = [
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
            'whereclause' => ''
        ];
        $utilitys['universities'] = $this->universities_model->universities(0, $get_params, 'GET');

        if (!empty($input_post)) {
            $create = $this->university_courses_model->create_and_edit(null, $input_post);

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
            'create_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        $utilitys['course_level'] = ['Diploma', 'UG', 'PG', 'Doctorate'];
        $utilitys['course_type'] = ['Full Time', 'Part Time', 'Distance', 'Online'];
        $get_params = [
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
            'whereclause' => ''
        ];
        $utilitys['universities'] = $this->universities_model->universities(0, $get_params, 'GET');

        if (!empty($input_post)) {
            $edit = $this->university_courses_model->create_and_edit($id, $input_post);

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

        $detailed = $this->university_courses_model->detailed($id);
        if ($detailed['status']) {
            $utilitys['data'] = $detailed['data'];

            // Format discount_duration for input daterangepicker
            if (!empty($detailed['data']['discount_duration_start']) && !empty($detailed['data']['discount_duration_end'])) {
                $utilitys['data']['discount_date_periode'] = date('d/m/Y', strtotime($detailed['data']['discount_duration_start'])) . ' - ' . date('d/m/Y', strtotime($detailed['data']['discount_duration_end']));
            }
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
        $detailed = $this->university_courses_model->detailed($id);
        $output = $this->university_courses_model->courses($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->university_courses_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
