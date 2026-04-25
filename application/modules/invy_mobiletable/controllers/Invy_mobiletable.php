<?php defined('BASEPATH') or exit('No direct script access allowed');

class Invy_mobiletable extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invy_mobiletable_model');
    }

    public function main()
    {
        $this->load->view('index');
    }

    public function table_test()
    {
        $output = [
            'response' => false,
            'error_message' => '',
            'data' => []
        ];
        $data_in_get = $this->input->get();

        if (!empty($data_in_get)) {
            $getting_parameter = [
                'select' => '*',
                'row_status' => 1,
                'outputtype' => 'data',
                'order_by' => [
                    'column' => 'id',
                    'order' => 'ASC'
                ],
                'limit' => [
                    'length' => @$data_in_get['limit'],
                    'start' => @$data_in_get['start']
                ],
                'bypass' => false,
                'whereclause' => ''
            ];
            $output_data_test = $this->invy_mobiletable_model->test(0, $getting_parameter, 'GET');

            if ($output_data_test['response'] && !empty($output_data_test['data']['data'])) {

                $output = [
                    'response' => true,
                    'error_message' => '',
                    'data' => [
                        'data' => []
                    ]
                ];

                foreach ($output_data_test['data']['data'] as $key => $value) {
                    $row = [];
                    $row['rowID'] = encryptcst($value['id']);
                    $row['content'] = '
                        <strong>' . $value['test'] . '</strong><br>
                        <small>Date Created : ' . date('d M Y', strtotime($value['created_at'])) . '</small>
                    ';

                    $row['action'] = '
                        <button class="btn btn-small btn-link-danger"><i class="fa-regular fa-trash-can"></i></button>
                    ';

                    $output['data']['data'][] = $row;
                }
            } else {
                $output = [
                    'response' => true,
                    'error_message' => 'Data empty',
                    'data' => []
                ];
            }
        }

        echo json_encode($output);
    }
}
