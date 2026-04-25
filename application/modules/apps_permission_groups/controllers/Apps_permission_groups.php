<?php defined('BASEPATH') or exit('No direct script access allowed');
class Apps_permission_groups extends CI_Controller
{
    protected $module = 'apps_permission_groups';
    protected $default_column_order = array(null, 'group_code', 'group_title', 'description', 'only_for', 'status');
    protected $default_order = [
        "column" => "group_code",
        "order" => "ASC"
    ];
    protected $default_column_select = '*';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('apps_permission_groups_model');
        $this->load->model('apps_permission_group_relations_model');
        $this->load->model('apps_module_features/apps_module_features_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->apps_permission_groups_model->query_builder($data_get);

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
        $datas = $this->apps_permission_groups_model->permission_groups(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["group_code"];  //primary key datas
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
                $row[] = $value['group_title'];
                $row[] = $value['description'];
                $row[] = $value['only_for'];
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

        $output = $this->apps_permission_groups_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    private function create_or_edit_relations($datas = null)
    {
        $output = null;

        if (!empty($datas['features'])) {
            foreach ($datas['features'] as $key => $value) {
                if (!empty($value)) {
                    $data_post = [
                        'group_code' => preg_replace('/[^A-Za-z_]/', '', 'GR_' . substr(strtoupper(trim($datas['group_code'])), 0, 15)),
                        'feature_code' => $value,
                    ];

                    $exist = $this->apps_permission_group_relations_model->detailed('', 'group_code = ' . $this->db->escape($datas['group_code'] . ' AND feature_code = ' . $this->db->escape($data_post['feature_code'])));
                    $feature = $this->apps_permission_group_relations_model->create_and_edit(@$exist['id'], $data_post);
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
                                    'message' => 'Delete data "' . @$datas['group_code'][$key] . '" -> "' . @$datas['feature_code'][$key] . '" successfully.',
                                    'level' => 'success',
                                ];
                            } else {
                                $output[] = [
                                    'code' => 'UPDATE',
                                    'message' => 'Update data "' . @$datas['group_code'][$key] . '" -> "' . @$datas['feature_code'][$key] . '" successfully.',
                                    'level' => 'success',
                                ];
                            }
                        } else {
                            $output[] = get_error_info($feature);
                        }

                        // Unset features that are still in use
                        if (($key = array_search($data_post['feature_code'], $datas['feature_exists'])) !== false) {
                            unset($datas['feature_exists'][$key]);
                        }
                    } elseif (!empty($feature)) {
                        $output[] = get_error_info($feature);
                    }
                }
            }

            // Deleting unused relationships
            if (!empty($datas['feature_exists'])) {
                foreach ($datas['feature_exists'] as $key => $value) {
                    $exist = $this->apps_permission_group_relations_model->detailed('', "group_code = " . $this->db->escape(preg_replace('/[^A-Za-z_]/', '', 'GR_' . substr(strtoupper(trim($datas['group_code'])), 0, 15))) . " AND feature_code = " . $this->db->escape($value));
                    $delete_relations = $this->apps_permission_group_relations_model->group_relations(@$exist['data']['id'], '', 'DELETE');
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

        $get_params = [
            'select' => '*',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'feature_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ''
        ];
        $utilitys['features'] = $this->apps_module_features_model->features(0, $get_params, 'GET');

        if (!empty($input_post)) {
            $create = $this->apps_permission_groups_model->create_and_edit(null, $input_post);

            if (@$create['status'] && (!empty(@$create['data']['insert_id']))) {
                $alert = [
                    'code' => 'CREATE',
                    'message' => 'Create data successfully.',
                    'level' => 'success',
                    'redirectUrl' => base_url() . "$this->module/edit/" . encryptcst($create['data']['insert_id'])
                ];

                $group_relations = $this->create_or_edit_relations(['group_code' => @$input_post['group_code'], 'features' => $input_post['features'], 'feature_exists' => @$utilitys['group_relations']]);
            } elseif (!empty($create)) {
                $alert = get_error_info($create);
            }
        }

        $this->load->view('index', ['alert' => $alert, 'toast' => @$group_relations, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function edit($id = null)
    {
        $internal = [
            'edit_form' => 'form-edit',
            'save_form_url' => ((checkPermission("$this->module/edit", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/edit/' . $id : ''),
            'module_main_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);
        $detailed = $this->apps_permission_groups_model->detailed($id);

        $get_params = [
            'select' => '*',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'feature_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => ''
        ];
        $utilitys['features'] = $this->apps_module_features_model->features(0, $get_params, 'GET');

        $get_params = [
            'select' => '*',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'feature_code',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => 'group_code = ' . $this->db->escape(@$detailed['data']['group_code'])
        ];
        $group_relations_data = $this->apps_permission_group_relations_model->group_relations(0, $get_params, 'GET');
        if (!empty($group_relations_data['data']['data'])) {
            $utilitys['group_relations'] = [];
            foreach ($group_relations_data['data']['data'] as $key => $value) {
                $utilitys['group_relations'][] = $value['feature_code'];
            }
        }

        if (!empty($input_post)) {
            $edit = $this->apps_permission_groups_model->create_and_edit($id, $input_post);

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

            $group_relations = $this->create_or_edit_relations(['group_code' => @$input_post['group_code'], 'features' => $input_post['features'], 'feature_exists' => @$utilitys['group_relations']]);
        }

        $group_relations_data = $this->apps_permission_group_relations_model->group_relations(0, $get_params, 'GET');
        if (!empty($group_relations_data['data']['data'])) {
            $utilitys['group_relations'] = [];
            foreach ($group_relations_data['data']['data'] as $key => $value) {
                $utilitys['group_relations'][] = $value['feature_code'];
            }
        }

        $detailed = $this->apps_permission_groups_model->detailed($id);
        if ($detailed['status']) {
            $detailed['data']['group_code'] = substr($detailed['data']['group_code'], 3);
            $utilitys['data'] = $detailed['data'];
        } else {
            sys_error_logs($detailed);
        }

        if (!empty($detailed['code'])) {
            $alert = $detailed;
        }

        $this->load->view('index', ['alert' => $alert, 'toastr' => @$group_relations, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->apps_permission_groups_model->detailed($id);
        $output = $this->apps_permission_groups_model->permission_groups($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->apps_permission_groups_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
