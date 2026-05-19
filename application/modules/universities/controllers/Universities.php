<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Universities extends CI_Controller
{
    protected $module = 'universities';
    protected $module_alias = 'UNI';
    protected $default_column_order = array(null, 'university_name', 'short_name', 'university_type', 'ugc_code', 'aicte_code', 'naac_grade', 'contact', 'email', 'website', 'country', 'state', 'city', 'district', 'address', 'postal_code', 'status');
    protected $default_order = [
        "column" => "university_name",
        "order" => "ASC"
    ];
    protected $default_column_select = '*';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('universities_model');
        $this->load->model('university_courses/university_courses_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->universities_model->query_builder($data_get);

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
        $datas = $this->universities_model->universities(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["university_name"];  //primary key datas
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

                $detailed_information_link = base_url() . "$this->module/detailed_info/" . encryptcst($value["id"]);

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
                $row[] = "<a title='University Detailed Information' href='$detailed_information_link' class='btn-link btn-edit'>$item</a>";
                $row[] = $value['short_name'];
                $row[] = $value['university_type'];
                $row[] = $value['ugc_code'];
                $row[] = $value['aicte_code'];
                $row[] = $value['naac_grade'];
                $row[] = $value['contact'];
                $row[] = $value['email'];
                $row[] = $value['website'];
                $row[] = $value['country'];
                $row[] = $value['state'];
                $row[] = $value['city'];
                $row[] = $value['district'];
                $row[] = $value['address'];
                $row[] = $value['postal_code'];
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

        $output = $this->universities_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function detailed_info($id = null)
    {
        $internal = [
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
            'university_courses_create_url' => ((permit_check('FT_UCS_CRT', get_user()['id'])) ? base_url() . 'university_courses/create' : ''),
            'university_courses_create_title' => 'Create item',
            'university_courses_create_form' => 'form-create',
        ];

        $utilitys = null;
        $id = decryptcst($id);
        $data_post = @$this->input->post();

        $utilitys['course_level'] = $this->university_courses_model->course_level();
        $utilitys['course_type'] = $this->university_courses_model->course_type();

        $detailed = $this->universities_model->detailed($id);
        if ($detailed['status']) {
            $getting_data = [
                'select' => 'university_courses.*, universities.university_name',
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
                'whereclause' => "university_id = '" . $id . "'"
            ];

            if (!empty($data_post['course_name'])) {
                $getting_data['whereclause'] .= ' AND CONCAT(course_name, " ", course_code) LIKE ' . $this->db->escape('%' . $data_post['course_name'] . '%');
            }

            if (!empty($data_post['course_level'])) {
                $getting_data['whereclause'] .= ' AND course_level = ' . $this->db->escape($data_post['course_level']);
            }

            if (!empty($data_post['course_type'])) {
                $getting_data['whereclause'] .= ' AND course_type = ' . $this->db->escape($data_post['course_type']);
            }

            $utilitys['courses'] = $this->university_courses_model->courses(0, $getting_data, 'GET');

            $utilitys['search'] = $data_post;
            $utilitys['data'] = $detailed['data'];
        } else {
            sys_error_logs($detailed);
        }

        $this->load->view('index', ['internal' => $internal, 'utilitys' => $utilitys]);
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

        $utilitys['university_type'] = $this->universities_model->university_type();
        $utilitys['naac_grade'] = $this->universities_model->naac_grade();
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

        if (!empty($input_post)) {
            $create = $this->universities_model->create_and_edit(null, $input_post);

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
                    'redirectUrl' => base_url() . "$this->module/detailed_info/" . encryptcst($create['data']['insert_id'])
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
            'create_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'edit_form' => 'form-edit',
            'save_form_url' => ((permit_check('FT_' . $this->module_alias . '_EDT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((permit_check('FT_' . $this->module_alias . '_MAI', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        $utilitys['university_type'] = $this->universities_model->university_type();
        $utilitys['naac_grade'] = $this->universities_model->naac_grade();
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

        if (!empty($input_post)) {
            $edit = $this->universities_model->create_and_edit($id, $input_post);

            sys_error_logs($edit);
            if (@$edit['status'] && (!empty(@$edit['data']['effected_id']))) {
                $alert = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => base_url() . "$this->module/detailed_info/" . encryptcst($edit['data']['effected_id'])
                ];
            } elseif (!empty($edit)) {
                $alert = get_error_info($edit);
            }
        }

        $detailed = $this->universities_model->detailed($id);
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
        $detailed = $this->universities_model->detailed($id);
        $output = $this->universities_model->universities($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->universities_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
