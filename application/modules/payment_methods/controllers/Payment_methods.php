<?php defined('BASEPATH') or exit('No direct script access allowed');

require('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Payment_methods extends CI_Controller
{
    protected $module = 'payment_methods';
    protected $module_alias = 'PMT';
    protected $default_column_order = array(null, 'method_code', 'method_name', 'category', 'account_name', 'account_identifier', 'bank_name', 'branch_name', 'ifsc_code', 'status');
    protected $default_order = [
        "column" => "method_name",
        "order" => "ASC"
    ];
    protected $default_column_select = '*';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('payment_methods_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->payment_methods_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function main()
    {
        $internal = [
            'create_url' => ((permit_check('FT_' . $this->module_alias . '_CRT', get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/create' : ''),
            'create_title' => 'Create item',
            'create_form' => 'form-create',
            'create_modal' => '#modal-create',
            'create_formtype' => 'ajax',
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
        $datas = $this->payment_methods_model->payment_methods(0, $get_params, 'GET');

        $tb_data = array();
        $no = @$data_get['start'];
        if ($datas['status'] && !empty($datas['data']['data'])) {
            foreach ($datas["data"]['data'] as $key => $value) {
                $no++;
                $row = array();
                $item = $value["method_code"];  //primary key datas
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
                    $edit = "<a title='Detail/Edit rows' href='$edit_link' class='btn-link btn-edit $disabled_edit' data-modalid='#modal-edit' data-formname='form-edit' data-formtype='ajax'>$item</a>";

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
                $row[] = $value['method_name'];
                $row[] = $value['category'];
                $row[] = $value['account_name'];
                $row[] = $value['account_identifier'];
                $row[] = $value['bank_name'];
                $row[] = $value['branch_name'];
                $row[] = $value['ifsc_code'];
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

        $output = $this->payment_methods_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function create()
    {
        $internal = [
            'create_form' => 'form-create'
        ];
        $utilitys = null;
        $input_post = @$this->input->post();

        $utilitys['category'] = $this->payment_methods_model->category();

        if (empty($input_post)) {
            $this->load->view("$this->module/create", ['utilitys' => $utilitys, 'internal' => $internal]);
        } else {
            $create = $this->payment_methods_model->create_and_edit(null, $input_post);

            sys_error_logs($create);
            echo json_encode($create);
        }
    }

    public function edit($id = null)
    {
        $internal = [
            'edit_form' => 'form-edit'
        ];
        $utilitys = null;
        $alert = null;
        $input_post = @$this->input->post();
        $id = decryptcst($id);

        $utilitys['category'] = $this->payment_methods_model->category();

        if (empty($input_post)) {
            $detailed = $this->payment_methods_model->detailed($id);
            if ($detailed['status']) {
                $utilitys['data'] = $detailed['data'];
            } else {
                sys_error_logs($detailed);
            }

            if (!empty($detailed['code'])) {
                $alert = $detailed;
            }

            $this->load->view("$this->module/edit", ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
        } else {
            $edit = $this->payment_methods_model->create_and_edit($id, $input_post);
            sys_error_logs($edit);
            echo json_encode($edit);
        }
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->payment_methods_model->detailed($id);
        $output = $this->payment_methods_model->payment_methods($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }

    public function restore($id)
    {
        $output = null;

        $id = decryptcst($id);
        $output = $this->payment_methods_model->restore($id);

        sys_error_logs($output);
        echo json_encode($output);
    }
}
