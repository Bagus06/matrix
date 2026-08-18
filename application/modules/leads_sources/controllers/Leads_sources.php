<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Leads_sources extends CI_Controller
{
    protected $module = 'leads_sources';
    protected $module_alias = 'LSS';
    protected $default_column_order = array(null, 'source_code', 'source_name', null, 'account', null, 'b2b_company_name', 'ref_name', null, 'discount', 'phone', 'email');
    protected $default_order = [
        "column" => "source_code",
        "order" => "ASC"
    ];
    protected $default_column_select = '*';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        sync_booked_number('source_code', 'leads_sources');

        $this->load->model('leads_sources_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->leads_sources_model->query_builder($data_get);

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

    public function source_information()
    {
        $output = [
            'status' => FALSE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => [
                'file' => __FILE__,
                'line' => __LINE__,
                'hint' => null
            ],
            'data' => null
        ];
        $input_get = @$this->input->get();

        if (!empty($input_get['source_code'])) {
            $source_information = '';
            $detailed_source = $this->leads_sources_model->detailed(0, 'source_code = ' . $this->db->escape($input_get['source_code']));

            if (!empty($detailed_source['data'])) {
                if (($detailed_source['data']['source_name'] === 'INSTAGRAM') || ($detailed_source['data']['source_name'] === 'FACEBOOK')) {
                    $source_information = $detailed_source['data']['source_name'] . ' - ' . $detailed_source['data']['account'];
                } elseif ($detailed_source['data']['source_name'] === 'WEBSITE') {
                    $source_information = $detailed_source['data']['url'];
                } elseif ($detailed_source['data']['source_name'] === 'B2B') {
                    $source_information = $detailed_source['data']['source_name'] . ' - Company : ' . $detailed_source['data']['b2b_company_name'];
                } elseif ($detailed_source['data']['source_name'] === 'REFERANCE') {
                    $source_information = $detailed_source['data']['source_name'] . ' - Name : ' . $detailed_source['data']['ref_name'];
                }

                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'source_information' => $source_information,
                        'source_name' => $detailed_source['data']['source_name']
                    ]
                ];
            }
        }

        echo json_encode($output);
    }

    public function generate_code()
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
        $sources = $this->leads_sources_model->sources();

        if (($input_get['source_id'] == 0) || !empty($input_get['source_id'])) {
            $output = [
                'status' => TRUE,
                'code' => null,
                'replace_code_value' => null,
                'redirectUrl' => null,
                'debug' => null,
                'data' => [
                    'source_name' => $sources[$input_get['source_id']]['source_name'],
                    'prefix' => $sources[$input_get['source_id']]['prefix'],
                    'code_generated' => last_booked_number($sources[$input_get['source_id']]['prefix'], 3)
                ]
            ];

            create_booked_number($output['data']['code_generated']);
        }

        sys_error_logs($output);

        echo json_encode($output);
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

        if (!empty($input_get['source_code'])) {
            $update = update_booked_number(0, $input_get['source_code']);

            if ($update) {
                $output = [
                    'status' => TRUE,
                    'code' => null,
                    'replace_code_value' => null,
                    'redirectUrl' => null,
                    'debug' => null,
                    'data' => [
                        'source_code' => $input_get['source_code']
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
        $datas = $this->leads_sources_model->leads_sources(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["source_code"];  //primary key datas
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
                $row[] = $value['source_name'];
                $row[] = ((empty($value['url'])) ? '<span class="text-muted font-italic">Empty</span>' : '<a href="https://' . str_replace('https://', '', str_replace('http://', '', @$value['url'])) . '" target="_blank" class="btn btn-sm btn-link font-weight-bold">Open Link</a>');
                $row[] = ((empty($value['account'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['account']);
                $row[] = ((empty($value['password'])) ? '<span class="text-muted font-italic">Empty</span>' : ((user_group_check('GR_ADMIN', get_user()['id'])) ? '<button class="btn btn-sm btn-link btn-showpassword" data-pass="' . decryptcst($value['password']) . '" showpass="false" title="Click to show password"><i class="fa-solid fa-eye-slash"></i></button>' : '********'));
                $row[] = ((empty($value['b2b_company_name'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['b2b_company_name']);
                $row[] = ((empty($value['ref_name'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['ref_name']);
                $row[] = ((empty($value['address'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['address']);
                $row[] = ((empty($value['discount'])) ? '<span class="text-muted font-italic">Empty</span>' : INR($value['discount']));
                $row[] = ((empty($value['phone'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['phone']);
                $row[] = ((empty($value['email'])) ? '<span class="text-muted font-italic">Empty</span>' : $value['email']);
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

        $output = $this->leads_sources_model->detailed(decryptcst($id), @$input_get['whereclause']);

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

        $utilitys['sources'] = $this->leads_sources_model->sources();

        if (!empty($input_post)) {
            if (($input_post['source_name'] == 0) || !empty($input_post['source_name'])) {
                $input_post['source_name'] = @$utilitys['sources'][$input_post['source_name']]['source_name'];
            }

            $create = $this->leads_sources_model->create_and_edit(null, $input_post);

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

        $utilitys['sources'] = $this->leads_sources_model->sources();

        if (!empty($input_post)) {
            if (($input_post['source_name'] == 0) || !empty($input_post['source_name'])) {
                $input_post['source_name'] = @$utilitys['sources'][$input_post['source_name']]['source_name'];
            }

            $edit = $this->leads_sources_model->create_and_edit($id, $input_post);

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

        $detailed = $this->leads_sources_model->detailed($id);
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
        $detailed = $this->leads_sources_model->detailed($id);
        $output = $this->leads_sources_model->leads_sources($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->leads_sources_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
