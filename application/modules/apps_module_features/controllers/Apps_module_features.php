<?php defined('BASEPATH') or exit('No direct script access allowed');

class Apps_module_features extends CI_Controller
{
    protected $module = 'apps_module_features';
    protected $default_column_select = 'apps_module_features.*';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('apps_module_features_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->apps_module_features_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function tb_feature()
    {
        $utilitys = null;
        $data_get = @$this->input->get();

        $get_params = [
            'select' => $this->default_column_select,
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
            'whereclause' => "module_id = '" . ((!empty(decryptcst(@$data_get['module_id']))) ? decryptcst(@$data_get['module_id']) : 0) . "'"
        ];
        $datas = $this->apps_module_features_model->features(0, $get_params, 'GET');

        if (!empty($datas['data']['data'])) {
            $utilitys = $datas['data'];
        }

        if (!empty($data_get)) {
            $utilitys['numrow'] = (int) ((@$data_get['last_num_row'] === '') ? 0 : ((int) @$data_get['last_num_row'] + 1));
            $utilitys['num'] = $utilitys['numrow'] + 1;

            $data_get['default_feature'] = filter_var($data_get['default_feature'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);;
            if (@$data_get['default_feature']) {
                $utilitys['data'][$utilitys['numrow'] + 0] = ['feature_code' => 'CRT', 'feature_title' => 'Create', 'description' => 'Page and action create', 'sys_lock' => true];
                $utilitys['data'][$utilitys['numrow'] + 1] = ['feature_code' => 'DEL', 'feature_title' => 'Delete', 'description' => 'Action Delete', 'sys_lock' => true];
                $utilitys['data'][$utilitys['numrow'] + 2] = ['feature_code' => 'EDT', 'feature_title' => 'Edit', 'description' => 'Page and action edit', 'sys_lock' => true];
                $utilitys['data'][$utilitys['numrow'] + 3] = ['feature_code' => 'MAI', 'feature_title' => 'Main', 'description' => 'Page Main', 'sys_lock' => true];
                $utilitys['data'][$utilitys['numrow'] + 4] = ['feature_code' => 'RCY', 'feature_title' => 'Reycle', 'description' => 'Page Recycle', 'sys_lock' => true];
                $utilitys['data'][$utilitys['numrow'] + 5] = ['feature_code' => 'RST', 'feature_title' => 'Restore', 'description' => 'Action Restore', 'sys_lock' => true];
            }
        }

        $utilitys['loop'] = 1;
        if (!empty($utilitys['data'])) {
            $utilitys['loop'] = (int) ((empty($utilitys['filtered_record'])) ? count($utilitys['data']) : $utilitys['filtered_record']);

            foreach ($utilitys['data'] as $key => $value) {
                $ex_ft_code = explode('_', $utilitys['data'][$key]['feature_code']);
                $utilitys['data'][$key]['feature_full_code'] = $utilitys['data'][$key]['feature_code'];
                $utilitys['data'][$key]['feature_code'] = ((empty(@$ex_ft_code[2])) ? $utilitys['data'][$key]['feature_code'] : @$ex_ft_code[2]);
            }
        }

        sys_error_logs(@$datas);
        $this->load->view('tb_feature', ['utilitys' => $utilitys]);
    }

    public function detailed($id = null)
    {
        $output = null;
        $input_get = @$this->input->get();

        $output = $this->apps_module_features_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function delete($id)
    {
        $output = null;

        $id = decryptcst($id);
        $detailed = $this->apps_module_features_model->detailed($id);
        $output = $this->apps_module_features_model->features($id, @$detailed['data']['row_status'], 'DELETE');

        echo json_encode($output);
    }
}
