<?php defined('BASEPATH') or exit('No direct script access allowed');

class Incentives extends CI_Controller
{
    protected $module = 'incentives';
    protected $module_alias = 'INC';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();

        $this->load->model('incentives_model');
    }

    public function index()
    {
        redirect($this->module . '/main');
    }

    public function main()
    {
        $this->require_page_permission('FT_' . $this->module_alias . '_MAI');
        $can_view_all = $this->can_view_all();
        $utilitys = [
            'can_view_all' => $can_view_all,
            'can_generate' => permit_check('FT_' . $this->module_alias . '_RUN', get_user()['id']),
            'can_approve' => permit_check('FT_' . $this->module_alias . '_APR', get_user()['id']),
            'can_mark_paid' => permit_check('FT_' . $this->module_alias . '_PAY', get_user()['id']),
            'counselors' => $can_view_all ? $this->incentives_model->counselors() : [],
            'default_period' => date('Y-m', strtotime('first day of last month'))
        ];
        $internal = [
            'setup_url' => permit_check('FT_' . $this->module_alias . '_SET', get_user()['id'])
                ? base_url($this->module . '/setup') : ''
        ];

        $this->load->view('index', ['utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function setup()
    {
        $this->require_page_permission('FT_' . $this->module_alias . '_SET');
        $alert = null;
        $input_post = $this->input->post();

        if (!empty($input_post)) {
            $validation = $this->validate_setup($input_post);
            if (!$validation['status']) {
                $alert = ['code' => 'VALIDATION', 'message' => $validation['message'], 'level' => 'error', 'redirectUrl' => null];
            } else {
                $saved = $this->incentives_model->save_configuration($validation['data']);
                sys_error_logs($saved);
                if ($saved['status']) {
                    $alert = [
                        'code' => 'UPDATE',
                        'message' => 'Incentive setup saved successfully.',
                        'level' => 'success',
                        'redirectUrl' => base_url($this->module . '/setup')
                    ];
                } else {
                    $alert = [
                        'code' => @$saved['code'],
                        'message' => @$saved['replace_code_value']['message'] ?: 'Unable to save incentive setup.',
                        'level' => 'error',
                        'redirectUrl' => null
                    ];
                }
            }
        }

        $utilitys = $this->incentives_model->configuration();
        $internal = [
            'save_form_url' => base_url($this->module . '/setup'),
            'edit_form' => 'form-incentive-setup',
            'module_main_url' => base_url($this->module . '/main')
        ];
        $this->load->view('index', ['alert' => $alert, 'utilitys' => $utilitys, 'internal' => $internal]);
    }

    public function overview()
    {
        $this->require_json_permission('FT_' . $this->module_alias . '_MAI');
        $period = $this->normalize_period($this->input->get('period'));
        if ($period === null) {
            return $this->json_response(['status' => false, 'message' => 'Invalid incentive month.'], 422);
        }

        $restrict_user_id = null;
        if ($this->can_view_all()) {
            $assigned_to = $this->input->get('assigned_to');
            if ($assigned_to !== null && $assigned_to !== '') {
                if (!ctype_digit((string) $assigned_to)) {
                    return $this->json_response(['status' => false, 'message' => 'Invalid counselor filter.'], 422);
                }
                $restrict_user_id = (int) $assigned_to;
            }
        } else {
            $restrict_user_id = (int) get_user()['id'];
        }

        try {
            $data = $this->incentives_model->report($period, $restrict_user_id);
        } catch (RuntimeException $exception) {
            return $this->json_response(['status' => false, 'message' => $exception->getMessage()], 422);
        }

        foreach ($data['items'] as &$item) {
            $item['student_edit_id'] = encryptcst($item['student_id']);
        }
        unset($item);

        return $this->json_response(['status' => true, 'data' => $data]);
    }

    public function generate()
    {
        $this->require_json_permission('FT_' . $this->module_alias . '_RUN');
        $period = $this->normalize_period($this->input->post('period'));
        if ($period === null) {
            return $this->json_response(['status' => false, 'message' => 'Invalid incentive month.'], 422);
        }
        $result = $this->incentives_model->generate_run($period);
        sys_error_logs($result);

        return $this->json_response([
            'status' => $result['status'],
            'message' => $result['status'] ? 'Incentive draft generated successfully.' : (@$result['replace_code_value']['message'] ?: 'Unable to generate incentive draft.'),
            'data' => $result['data']
        ], $result['status'] ? 200 : 422);
    }

    public function approve()
    {
        return $this->update_status('FT_' . $this->module_alias . '_APR', 'APPROVED', 'Incentive run approved and frozen.');
    }

    public function mark_paid()
    {
        return $this->update_status('FT_' . $this->module_alias . '_PAY', 'PAID', 'Incentive run marked as paid.');
    }

    private function update_status($feature_code, $status, $success_message)
    {
        $this->require_json_permission($feature_code);
        $run_id = $this->input->post('run_id');
        if (!ctype_digit((string) $run_id) || (int) $run_id < 1) {
            return $this->json_response(['status' => false, 'message' => 'Invalid incentive run.'], 422);
        }
        $result = $this->incentives_model->change_run_status((int) $run_id, $status);
        sys_error_logs($result);

        return $this->json_response([
            'status' => $result['status'],
            'message' => $result['status'] ? $success_message : (@$result['replace_code_value']['message'] ?: 'Unable to update incentive status.'),
            'data' => $result['data']
        ], $result['status'] ? 200 : 422);
    }

    private function validate_setup(array $input)
    {
        $effective_month = $this->normalize_period(@$input['effective_month']);
        $plan_name = trim((string) @$input['plan_name']);
        $qualifying_bv = filter_var(@$input['qualifying_bv'], FILTER_VALIDATE_FLOAT);
        $release_percent = filter_var(@$input['initial_release_percent'], FILTER_VALIDATE_FLOAT);
        $pay_day = filter_var(@$input['pay_day'], FILTER_VALIDATE_INT);

        if ($plan_name === '' || $effective_month === null) {
            return ['status' => false, 'message' => 'Plan name and effective month are required.'];
        }
        if ($qualifying_bv === false || $qualifying_bv < 0 || floor($qualifying_bv) != $qualifying_bv) {
            return ['status' => false, 'message' => 'Qualifying BV must be a non-negative whole number.'];
        }
        if ($release_percent === false || $release_percent <= 0 || $release_percent >= 100) {
            return ['status' => false, 'message' => 'Initial release percentage must be greater than 0 and below 100.'];
        }
        if ($pay_day === false || $pay_day < 1 || $pay_day > 28) {
            return ['status' => false, 'message' => 'Pay day must be between 1 and 28.'];
        }

        $btech_keyword = trim((string) @$input['btech_keyword']);
        $btech_bv = filter_var(@$input['btech_bv'], FILTER_VALIDATE_FLOAT);
        $other_bv = filter_var(@$input['other_bv'], FILTER_VALIDATE_FLOAT);
        $btech_initial = filter_var(@$input['btech_initial_payment'], FILTER_VALIDATE_FLOAT);
        $other_initial = filter_var(@$input['other_initial_payment'], FILTER_VALIDATE_FLOAT);
        if ($btech_keyword === '' || $btech_bv === false || $other_bv === false || $btech_bv <= 0 || $other_bv <= 0
            || $btech_initial === false || $other_initial === false || $btech_initial < 0 || $other_initial < 0) {
            return ['status' => false, 'message' => 'Complete the B.Tech and Other course rules with valid positive BV values.'];
        }

        $from_values = @$input['slab_from'];
        $to_values = @$input['slab_to'];
        $rate_values = @$input['slab_rate'];
        if (!is_array($from_values) || !is_array($to_values) || !is_array($rate_values)
            || count($from_values) < 1 || count($from_values) !== count($to_values) || count($from_values) !== count($rate_values)) {
            return ['status' => false, 'message' => 'At least one complete incentive slab is required.'];
        }

        $slabs = [];
        $expected_from = $qualifying_bv + 1;
        foreach ($from_values as $index => $from_value) {
            $from = filter_var($from_value, FILTER_VALIDATE_FLOAT);
            $to_raw = trim((string) @$to_values[$index]);
            $to = $to_raw === '' ? null : filter_var($to_raw, FILTER_VALIDATE_FLOAT);
            $rate = filter_var(@$rate_values[$index], FILTER_VALIDATE_FLOAT);
            if ($from === false || floor($from) != $from || $from != $expected_from || $rate === false || $rate < 0) {
                return ['status' => false, 'message' => 'Incentive slabs must be contiguous whole-BV ranges beginning after qualifying BV.'];
            }
            $is_last = $index === count($from_values) - 1;
            if ((!$is_last && ($to === false || $to === null || floor($to) != $to || $to < $from)) || ($is_last && $to !== null)) {
                return ['status' => false, 'message' => 'Every slab except the final slab needs an upper BV value; the final slab must remain open-ended.'];
            }
            $slabs[] = [
                'from_bv' => $from,
                'to_bv' => $to,
                'rate_per_bv' => $rate
            ];
            if ($to !== null) {
                $expected_from = $to + 1;
            }
        }

        return [
            'status' => true,
            'data' => [
                'plan_id' => (int) @$input['plan_id'],
                'plan_name' => substr($plan_name, 0, 100),
                'effective_from' => $effective_month . '-01',
                'qualifying_bv' => $qualifying_bv,
                'initial_release_percent' => $release_percent,
                'pay_day' => $pay_day,
                'rules' => [
                    [
                        'category_code' => 'BTECH',
                        'category_name' => 'B.Tech',
                        'match_keyword' => substr($btech_keyword, 0, 50),
                        'bv' => $btech_bv,
                        'initial_payment' => $btech_initial
                    ],
                    [
                        'category_code' => 'OTHER',
                        'category_name' => 'Other',
                        'match_keyword' => null,
                        'bv' => $other_bv,
                        'initial_payment' => $other_initial
                    ]
                ],
                'slabs' => $slabs
            ]
        ];
    }

    private function can_view_all()
    {
        $user = get_user();
        return strtoupper($user['username']) === 'DEVELOPER' || user_group_check('GR_ADMIN', $user['id']);
    }

    private function normalize_period($period)
    {
        $period = trim((string) $period);
        $year = null;
        $month = null;

        if (preg_match('/^(\d{4})-(\d{1,2})$/', $period, $matches)) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
        } elseif (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $period, $matches)
            && checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1])) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
        } elseif (preg_match('/^(\d{1,2})[\/-](\d{4})$/', $period, $matches)) {
            $year = (int) $matches[2];
            $month = (int) $matches[1];
        }

        if ($year === null || $year < 1900 || $year > 9999 || !checkdate($month, 1, $year)) {
            return null;
        }

        return sprintf('%04d-%02d', $year, $month);
    }

    private function require_page_permission($feature_code)
    {
        if (!permit_check($feature_code, get_user()['id'])) {
            show_error('You do not have permission to access this incentive feature.', 403, 'Forbidden');
            exit;
        }
    }

    private function require_json_permission($feature_code)
    {
        if (!permit_check($feature_code, get_user()['id'])) {
            $this->json_response(['status' => false, 'message' => 'You do not have permission to perform this action.'], 403);
            exit;
        }
    }

    private function json_response(array $payload, $status_code = 200)
    {
        return $this->output->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }
}
