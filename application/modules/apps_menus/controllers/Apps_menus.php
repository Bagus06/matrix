<?php defined('BASEPATH') or exit('No direct script access allowed');

class Apps_menus extends CI_Controller
{
    protected $module = 'apps_menus';
    protected $default_order = [
        "column" => "order_menu",
        "order" => "ASC"
    ];
    protected $default_column_select = '*';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('apps_menus_model');
        $this->load->model('apps_module_features/apps_module_features_model');
    }

    public function query_builder()
    {
        $data_get = $this->input->get();
        $output = $this->apps_menus_model->query_builder($data_get);

        sys_error_logs($output);
        echo json_encode($output);
    }

    public function main()
    {
        # Uncomment for use user login check
        check_auth();

        $internal = [
            'save_form_url' => ((checkPermission("$this->module/main", get_user()['id'])) ? base_url() . $this->uri->rsegments[1] . '/main' : ''),
            'edit_title' => 'edit item',
            'edit_form' => 'form-edit'
        ];
        $utilitys = null;

        $get_params = [
            'select' => 'apps_module_features.id, feature_code, module_title, feature_title',
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
        if (!empty($utilitys['features']['data']['data'])) {
            $utilitys['features']['data'] = $utilitys['features']['data']['data'];
        } else {
            sys_error_logs($utilitys['features']);
        }

        $this->load->view('index', ['utilitys' => $utilitys, 'internal' => $internal]);
    }

    private function build_menu_tree_view(array $data)
    {
        $output = '';
        $input_get = $this->input->get();

        foreach ($data as $key => $value) {
            if (!empty(@$value['url']) || $value['children']) {
                $output .= '
                     <li class="nav-item">
                        <a href="' . ((!empty(@$value['url'])) ? base_url() . strtolower(@$value['url']) : '') . '" class="nav-link ' . ((hex2bin(@$input_get['menu_open']) === strtolower(@$value['url'])) ? 'active' : '') . '">
                            <i class="nav-icon ' . $value['icon'] . '"></i>
                            <p>' . $value['display_title'] . ((!empty($value['children'])) ? '<i class="right fas fa-angle-left"></i>' : '') . '</p>
                        </a>
                        ' . ((!empty($value['children'])) ? '
                        <ul class="nav nav-treeview">
                            ' . $this->build_menu_tree_view($value['children']) . '
                        </ul>
                        ' : '') . '
                    </li>
                ';
            }
        }

        return $output;
    }

    public function sidebar_menu()
    {
        $utilitys = null;
        $input_get = $this->input->get();
        $user_id = ((empty(decryptcst(@$input_get['user_id']))) ? get_user()['id'] : decryptcst(@$input_get['user_id']));
        $escaped = array_map([$this->db, 'escape'], feature_accessed($user_id));
        $features  = '(' . implode(', ', $escaped) . ')';

        $get_params = [
            'select' => '*',
            'row_status' => 1,
            'outputtype' => 'data',
            'order_by' => [
                'column' => 'order_menu',
                'order' => 'ASC'
            ],
            'limit' => [
                'length' => -1,
                'start' => 0
            ],
            'bypass' => false,
            'whereclause' => 'feature_code IN ' . $features . ' OR feature_code IS NULL'
        ];
        $data_menu = $this->apps_menus_model->menus(0, $get_params, 'GET');

        if ($data_menu['status'] && !empty($data_menu['data']['data'])) {
            $menu_tree_array = $this->apps_menus_model->build_menu_tree($data_menu['data']['data']);
            $utilitys['menu_html'] = $this->build_menu_tree_view($menu_tree_array);
        }

        $this->load->view('sidebar-menu', ['utilitys' => $utilitys]);
    }

    public function menu_tree()
    {
        $output = null;
        $output = $this->apps_menus_model->menu_tree();

        echo json_encode($output, JSON_PRETTY_PRINT);
    }

    public function detailed($id = null)
    {
        $output = null;
        $input_get = @$this->input->get();

        $output = $this->apps_menus_model->detailed(decryptcst($id), @$input_get['whereclause']);

        echo json_encode($output);
    }

    public function create_or_edit_menus()
    {
        $output = null;
        $input_post = $this->input->post();

        if (!empty($input_post['id'])) {
            foreach ($input_post['id'] as $key => $value) {
                $data_post = [
                    'feature_code' => $input_post['feature_code'][$key],
                    'icon' => $input_post['icon'][$key],
                    'display_title' => $input_post['display_title'][$key],
                    'description' => $input_post['description'][$key],
                    'url' => $input_post['url'][$key],
                    'menu_status' => @$input_post['menu_status'][$key],
                    'order_menu' => @$input_post['order_menu'][$key],
                    'parent_menu_id' => @$input_post['parent_menu_id'][$key],
                    'visible' => @$input_post['visible'][$key],
                    'sys_lock' => @$input_post['sys_lock'][$key],
                ];
                $feature = $this->apps_menus_model->create_and_edit($value, $data_post);

                if (@$feature['status']) {
                    if (!empty(@$feature['data']['insert_id'])) {
                        $output[] = [
                            'code' => 'CREATE',
                            'message' => 'Create data successfully.',
                            'level' => 'success',
                        ];
                    } elseif (!empty(@$feature['data']['effected_id'])) {
                        $output[] = [
                            'code' => 'UPDATE',
                            'message' => 'Update data "' . @$input_post['display_title'][$key] . '" successfully.',
                            'level' => 'success',
                        ];
                    } else {
                        $output[] = get_error_info($feature);
                    }
                } elseif (!empty($feature)) {
                    $output[] = get_error_info($feature);
                }
            }
        }

        echo json_encode($output);
    }

    public function delete($id)
    {
        $output = null;
        $output = $this->apps_menus_model->menus($id, 0, 'DELETE');

        sys_error_logs($output);
        echo json_encode($output);
    }
}
