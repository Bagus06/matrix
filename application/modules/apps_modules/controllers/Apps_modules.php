<?php defined('BASEPATH') or exit('No direct script access allowed');
class Apps_modules extends CI_Controller
{
    protected $module = 'apps_modules';
    protected $default_column_order = array(null, 'module_code', 'module_title', null, 'status', 'created_at', 'updated_at');
    protected $default_order = [
        "column" => "module_code",
        "order" => "ASC"
    ];
    protected $default_column_select = 'apps_modules.id, module_code, module_title, description, status, created_at, updated_at';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('apps_modules_model');
        $this->load->model('apps_module_features/apps_module_features_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->apps_modules_model->query_builder($data_get);

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
        $datas = $this->apps_modules_model->apps_modules(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["module_code"];  //primary key datas
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
                $row[] = $value['module_title'];
                $row[] = $value['description'];
                $row[] = $value['status'];
                $row[] = date('d M Y', strtotime($value['created_at']));
                $row[] = date('d M Y', strtotime($value['updated_at']));
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

        $output = $this->apps_modules_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    private function create_or_edit_feature($datas = null)
    {
        $output = null;

        if (!empty($datas['ft_id'])) {
            foreach ($datas['ft_id'] as $key => $value) {
                $id = decryptcst($value);
                if ($datas['ft_delete'][$key] && !empty($id)) {
                    $detailed = $this->apps_module_features_model->detailed($id);

                    if ($detailed['data']['sys_lock']) {
                        $feature = [
                            'status' => FALSE,
                            'code' => 'FEAT-DEL-E003',
                            'replace_code_message' => null,
                            'redirectUrl' => null,
                            'debug' => null,
                            'data' => null
                        ];
                    } else {
                        $feature = $this->apps_module_features_model->features($id, '', 'DELETE');
                    }
                } elseif (!$datas['ft_delete'][$key]) {
                    $data_post = [
                        'module_id' => $datas['module_id'],
                        'module_code' => $datas['module_code'],
                        'feature_code' => $datas['ft_feature_code'][$key],
                        'feature_title' => $datas['ft_feature_title'][$key],
                        'description' => $datas['ft_description'][$key],
                        'status' => @$datas['ft_status'][$key],
                        'sys_lock' => @$datas['ft_sys_lock'][$key],
                    ];

                    $feature = $this->apps_module_features_model->create_and_edit($id, $data_post);
                }

                if (@$feature['status']) {
                    if (!empty(@$feature['data']['insert_id'])) {
                        $output[] = [
                            'code' => 'CREATE',
                            'message' => 'Create data successfully.',
                            'level' => 'success',
                        ];
                    } elseif (!empty(@$feature['data']['effected_id'])) {
                        if ($datas['ft_delete'][$key] && !empty($id)) {
                            $output[] = [
                                'code' => 'DELETE',
                                'message' => 'Delete data "' . @$datas['ft_feature_code'][$key] . '" successfully.',
                                'level' => 'success',
                            ];
                        } else {
                            $output[] = [
                                'code' => 'UPDATE',
                                'message' => 'Update data "' . @$datas['ft_feature_code'][$key] . '" successfully.',
                                'level' => 'success',
                            ];
                        }
                    } else {
                        $output[] = get_error_info($feature);
                    }
                } elseif (!empty($feature)) {
                    $output[] = get_error_info($feature);
                }
            }
        }

        return $output;
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

        if (!empty($input_post)) {
            $create = $this->apps_modules_model->create_and_edit(null, $input_post);

            if (@$create['status'] && (!empty(@$create['data']['insert_id']))) {
                $input_post['module_id'] = @$create['data']['insert_id'];
                $input_post['module_code'] = @$input_post['module_code'];
                $feature = $this->create_or_edit_feature($input_post);

                $alert = [
                    'code' => 'CREATE',
                    'message' => 'Create data successfully.',
                    'level' => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($create['data']['insert_id'])
                ];
            } elseif (!empty($create)) {
                $alert = get_error_info($create);
            }

            sys_error_logs($create);
        }

        $this->load->view('index', ['alert' => $alert, 'toastr' => @$feature, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function edit($id = null)
    {
        $internal = [
            'edit_form' => 'form-edit',
            'save_form_url' => ((checkPermission("$this->module/edit", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
            'create_url' => ((checkPermission("$this->module/create", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        if (!empty($input_post)) {
            $edit = $this->apps_modules_model->create_and_edit($id, $input_post);

            $input_post['module_id'] = @$id;
            $input_post['module_code'] = @$input_post['module_code'];
            $feature = $this->create_or_edit_feature($input_post);

            if (@$edit['status'] && (!empty(@$edit['data']['effected_id']))) {
                $alert = [
                    'code' => 'UPDATE',
                    'message' => 'Update data successfully.',
                    'level'   => 'success',
                ];
            } elseif (!empty($edit)) {
                $alert = get_error_info($edit);
            }
            sys_error_logs($edit);
        }

        $detailed = $this->apps_modules_model->detailed($id);
        if ($detailed['status']) {
            $detailed['data']['module_code'] = substr($detailed['data']['module_code'], 3);
            $utilitys['data'] = $detailed['data'];
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        $this->load->view('index', ['alert' => $alert, 'toastr' => @$feature, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->apps_modules_model->detailed($id);

        if (@$detailed['data']['sys_lock']) {
            $output = [
                'status' => FALSE,
                'code' => 'MODL-DEL-E003',
                'replace_code_message' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => null
            ];
        } else {
            $output = $this->apps_modules_model->apps_modules($id, @$detailed['data']['row_status'], 'DELETE');
        }

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->apps_modules_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
