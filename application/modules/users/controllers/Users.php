<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class users extends CI_Controller
{
    protected $module = 'users';
    protected $default_column_order = array(null, 'username', 'email', 'phone', 'account_status');
    protected $default_order = [
        "column" => "username",
        "order" => "ASC"
    ];
    protected $default_column_select = '*, users.id';

    public function __construct()
    {
        parent::__construct();
        check_auth();

        $this->load->model('users_model');
        $this->load->model('geolocation/countries_model');
        $this->load->model('apps_permission_groups/apps_permission_groups_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->users_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function main()
    {
        $internal = [
            'create_url' => ((checkPermission("$this->module/create", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'recycle_url' => ((checkPermission("$this->module/recycle", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/recycle' : ''),
        ];
        $utilitys = null;

        $this->load->view('index', ['utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function recycle()
    {
        $internal = [
            'module_main_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
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
        $datas = $this->users_model->users(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["username"];  //primary key datas
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
                    if (checkPermission("$this->module/edit", get_user()['id'])) {
                        $disabled_edit = "";
                        $edit_link = base_url() . "$this->module/edit/" . encryptcst($value["id"]);
                    }
                    # Self Update
                    // $edit = "<a title='Detail/Edit rows' href='$edit_link' class='btn-link btn-edit $disabled_edit' data-modalid='#modal-edit' data-formname='form-edit' data-formtype='ajax'>$item</a>";
                    $edit = "<a title='Detail/Edit rows' href='$edit_link' class='btn-link btn-edit $disabled_edit'>$item</a>";

                    # SOFT DELETE
                    if (checkPermission("$this->module/delete", get_user()['id'])) {
                        $disabled_delete = "";
                        $delete_link = encryptcst($value["id"]);
                    }
                    $delete = "<button title='Delete item - $item' data-id='$delete_link' data-item='$item' class='btn btn-link btn-delete $disabled_delete'><i class='fa-solid fa-trash'></i></button>";
                } elseif ($data_get["row_status"] == 0) { // In recycle page
                    # RESTORE
                    if (checkPermission("$this->module/restore", get_user()['id'])) {
                        $disabled_restore = "";
                    }
                    $restore = "<button title='Restore item - $item' data-id='" . encryptcst($value["id"]) . "' data-item='$item' class='btn btn-link btn-restore $disabled_restore'><i class='fa-solid fa-trash-arrow-up'></i></button>";

                    # PERMANENT DELETE
                    if (checkPermission("$this->module/delete", get_user()['id'])) {
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
                $row[] = $value['email'];
                $row[] = $value['phone'];
                $row[] = $value['account_status'];
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

        $output = $this->users_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function create()
    {
        $internal = [
            'create_form' => 'form-create',
            'save_form_url' => ((checkPermission("$this->module/create", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'module_main_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();

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

        $data_get_countries = [
            'select' => 'id, group_code, group_title',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'group_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ''
        ];
        $utilitys['permission_group'] = $this->apps_permission_groups_model->permission_groups(0, $data_get_countries, 'GET');

        if (!empty($input_post)) {
            $create = $this->users_model->create_and_edit(null, $input_post);

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

    public function profile_upload($id = null)
    {
        $output = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        if (!empty($input_post)) {
            $edit = $this->users_model->profile_upload($id, $input_post);

            sys_error_logs($edit);
            if (@$edit['status'] && ((@$edit['code'] === 'USRS-PPC-E001') || !empty(@$edit['data']['effected_id']))) {
                $output = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($id)
                ];
            } elseif (!empty($edit)) {
                $output = get_error_info($edit);
            }
        }

        echo json_encode($output);
    }

    public function edit($id = null)
    {
        $internal = [
            'create_url' => ((checkPermission("$this->module/create", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'edit_form' => 'form-edit',
            'save_form_url' => ((checkPermission("$this->module/edit", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

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

        $data_get_countries = [
            'select' => 'id, group_code, group_title',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'group_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ''
        ];
        $utilitys['permission_group'] = $this->apps_permission_groups_model->permission_groups(0, $data_get_countries, 'GET');

        if (!empty($input_post)) {
            $edit = $this->users_model->create_and_edit($id, $input_post);

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

        $detailed = $this->users_model->detailed($id);
        if ($detailed['status']) {
            $utilitys['data'] = $detailed['data'];
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        if (empty($utilitys['data']['permission_group'])) {
            $utilitys['data']['permission_group'] = [];
        } else {
            $utilitys['data']['permission_group'] = json_decode($utilitys['data']['permission_group'], true);
        }
        $this->load->view('index', ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->users_model->detailed($id);
        $output = $this->users_model->users($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->users_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
