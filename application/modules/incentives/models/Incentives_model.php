<?php defined('BASEPATH') or exit('No direct script access allowed');

class Incentives_model extends CI_model
{
    protected $module_name = 'incentives';
    protected $error_prefix = 'INC';
    protected $tb1 = 'incentive_plans';
    protected $tb2 = 'incentive_course_rules';
    protected $tb3 = 'incentive_slabs';
    protected $tb4 = 'incentive_runs';
    protected $tb5 = 'incentive_run_details';
    protected $tb6 = 'incentive_run_items';

    public function __construct()
    {
        parent::__construct();
    }

    public function configuration()
    {
        $query = "SELECT * FROM $this->tb1
                  WHERE row_status = 1
                  ORDER BY effective_from DESC, id DESC
                  LIMIT 1";
        $plan = $this->db->query($query)->row_array();

        if (empty($plan)) {
            return [
                'plan' => null,
                'rules' => [],
                'slabs' => [],
                'versions' => [],
                'matching_courses' => []
            ];
        }

        $plan_id = (int) $plan['id'];
        $query = "SELECT * FROM $this->tb2
                  WHERE plan_id = $plan_id AND row_status = 1
                  ORDER BY priority ASC";
        $rules = $this->db->query($query)->result_array();

        $query = "SELECT * FROM $this->tb3
                  WHERE plan_id = $plan_id AND row_status = 1
                  ORDER BY slab_order ASC";
        $slabs = $this->db->query($query)->result_array();

        $query = "SELECT id, plan_name, effective_from, effective_to, status
                  FROM $this->tb1
                  WHERE row_status = 1
                  ORDER BY effective_from DESC";
        $versions = $this->db->query($query)->result_array();

        $btech_rule = $this->rule_by_code($rules, 'BTECH');

        return [
            'plan' => $plan,
            'rules' => $rules,
            'slabs' => $slabs,
            'versions' => $versions,
            'matching_courses' => $this->matching_courses(@$btech_rule['match_keyword']),
            'locked' => $this->plan_is_locked($plan['id'])
        ];
    }

    public function counselors()
    {
        $query = "SELECT u.id, TRIM(up.name) name
                  FROM users u
                  INNER JOIN user_profiles up
                    ON up.user_id = u.id AND up.row_status = 1
                  WHERE u.row_status = 1
                    AND JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_MKTST'))
                    AND NOT JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_ADMIN'))
                    AND NULLIF(TRIM(up.name), '') IS NOT NULL
                  ORDER BY up.name ASC";

        return $this->db->query($query)->result_array();
    }

    public function matching_courses($keyword)
    {
        $keyword = $this->normalise_keyword($keyword);
        if ($keyword === '') {
            return [];
        }

        $query = "SELECT id, course_code, course_name, course_type
                  FROM university_courses
                  WHERE row_status = 1
                  ORDER BY course_name ASC";
        $rows = $this->db->query($query)->result_array();

        return array_values(array_filter($rows, function ($row) use ($keyword) {
            return strpos($this->normalise_keyword($row['course_code'] . ' ' . $row['course_name']), $keyword) !== false;
        }));
    }

    public function save_configuration(array $data)
    {
        $user_id = (int) get_user()['id'];
        $plan_id = (int) @$data['plan_id'];
        $existing = null;
        if (!empty($plan_id)) {
            $query = "SELECT * FROM $this->tb1
                      WHERE id = $plan_id AND row_status = 1
                      LIMIT 1";
            $existing = $this->db->query($query)->row_array();
        }
        $locked = $existing ? $this->plan_is_locked($existing['id']) : false;

        if ($locked && $existing['effective_from'] === $data['effective_from']) {
            return $this->error('SET-E001', 'This plan is already used by an approved run. Choose a new effective month to create a new version.');
        }

        $effective_from = $this->db->escape($data['effective_from']);
        $excluded_plan_id = $plan_id ?: 0;
        $query = "SELECT * FROM $this->tb1
                  WHERE effective_from = $effective_from
                    AND row_status = 1
                    AND id != $excluded_plan_id
                  LIMIT 1";
        $duplicate = $this->db->query($query)->row_array();
        if (!empty($duplicate)) {
            return $this->error('SET-E002', 'A plan already starts in the selected effective month.');
        }

        $this->db->trans_begin();
        $plan_data = [
            'plan_name' => $data['plan_name'],
            'effective_from' => $data['effective_from'],
            'effective_to' => null,
            'qualifying_bv' => $data['qualifying_bv'],
            'initial_release_percent' => $data['initial_release_percent'],
            'pay_day' => $data['pay_day'],
            'currency' => 'INR',
            'status' => 'ACTIVE',
            'row_status' => 1,
            'updated_by' => $user_id
        ];

        if (!empty($existing) && !$locked) {
            $saved_plan_id = (int) $existing['id'];
            $this->update_row($this->tb1, $plan_data, "id = $saved_plan_id");
            $this->db->query("DELETE FROM $this->tb2 WHERE plan_id = $saved_plan_id");
            $this->db->query("DELETE FROM $this->tb3 WHERE plan_id = $saved_plan_id");
        } else {
            $plan_data['created_by'] = $user_id;
            $this->insert_row($this->tb1, $plan_data);
            $saved_plan_id = (int) $this->db->insert_id();
        }

        $query = "SELECT * FROM $this->tb1
                  WHERE row_status = 1
                    AND id != $saved_plan_id
                    AND effective_from < $effective_from
                    AND (effective_to IS NULL OR effective_to >= $effective_from)
                  ORDER BY effective_from DESC
                  LIMIT 1";
        $previous = $this->db->query($query)->row_array();
        if (!empty($previous)) {
            $previous_id = (int) $previous['id'];
            $this->update_row($this->tb1, [
                'effective_to' => date('Y-m-d', strtotime($data['effective_from'] . ' -1 day')),
                'updated_by' => $user_id
            ], "id = $previous_id");
        }

        foreach ($data['rules'] as $priority => $rule) {
            $this->insert_row($this->tb2, [
                'plan_id' => $saved_plan_id,
                'category_code' => $rule['category_code'],
                'category_name' => $rule['category_name'],
                'match_keyword' => $rule['category_code'] === 'BTECH' ? $rule['match_keyword'] : null,
                'bv' => $rule['bv'],
                'initial_payment' => $rule['initial_payment'],
                'priority' => $priority + 1,
                'row_status' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]);
        }

        foreach ($data['slabs'] as $index => $slab) {
            $this->insert_row($this->tb3, [
                'plan_id' => $saved_plan_id,
                'slab_order' => $index + 1,
                'from_bv' => $slab['from_bv'],
                'to_bv' => $slab['to_bv'],
                'rate_per_bv' => $slab['rate_per_bv'],
                'row_status' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]);
        }

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->db->trans_rollback();
            return $this->error('SET-E003', 'Unable to save incentive setup.', $error['message']);
        }

        $this->db->trans_commit();
        return $this->success(['plan_id' => $saved_plan_id]);
    }

    public function report($period, $restrict_user_id = null)
    {
        $run = $this->run_by_period($period);
        if (!empty($run)) {
            return $this->snapshot_report($run, $restrict_user_id);
        }

        return $this->calculate_period($period, $restrict_user_id);
    }

    public function generate_run($period)
    {
        $existing = $this->run_by_period($period);
        if (!empty($existing) && in_array($existing['status'], ['APPROVED', 'PAID'], true)) {
            return $this->error('RUN-E001', 'Approved or paid incentive runs cannot be regenerated.');
        }

        try {
            $report = $this->calculate_period($period, null);
        } catch (RuntimeException $exception) {
            return $this->error('RUN-E002', $exception->getMessage());
        }

        $user_id = (int) get_user()['id'];
        $this->db->trans_begin();
        $run_data = [
            'period_start' => $report['period']['start'],
            'period_end' => $report['period']['end'],
            'status' => 'DRAFT',
            'currency' => $report['currency'],
            'current_payable' => $report['summary']['current_payable'],
            'balance_released' => $report['summary']['balance_released'],
            'total_payable' => $report['summary']['total_payable'],
            'generated_by' => $user_id,
            'generated_at' => date('Y-m-d H:i:s'),
            'row_status' => 1,
            'updated_by' => $user_id
        ];

        if (!empty($existing)) {
            $run_id = (int) $existing['id'];
            $this->update_row($this->tb4, $run_data, "id = $run_id");
            $this->db->query("DELETE FROM $this->tb6 WHERE run_id = $run_id");
            $this->db->query("DELETE FROM $this->tb5 WHERE run_id = $run_id");
        } else {
            $run_data['created_by'] = $user_id;
            $this->insert_row($this->tb4, $run_data);
            $run_id = (int) $this->db->insert_id();
        }

        $detail_ids = [];
        foreach ($report['rows'] as $row) {
            $detail = [
                'run_id' => $run_id,
                'counselor_user_id' => $row['user_id'],
                'counselor_name' => $row['counselor'],
                'btech_admissions' => $row['btech_admissions'],
                'other_admissions' => $row['other_admissions'],
                'total_bv' => $row['total_bv'],
                'eligible_bv' => $row['eligible_bv'],
                'gross_incentive' => $row['gross_incentive'],
                'current_payable' => $row['current_payable'],
                'balance_released' => $row['balance_released'],
                'total_payable' => $row['total_payable'],
                'pay_date' => $row['pay_date'],
                'row_status' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ];
            $this->insert_row($this->tb5, $detail);
            $detail_ids[$row['user_id']] = (int) $this->db->insert_id();
        }

        foreach ($report['items'] as $item) {
            if (empty($detail_ids[$item['user_id']])) {
                continue;
            }
            $this->insert_row($this->tb6, [
                'run_id' => $run_id,
                'detail_id' => $detail_ids[$item['user_id']],
                'plan_id' => $item['plan_id'],
                'student_id' => $item['student_id'],
                'student_number' => $item['student_number'],
                'lead_id' => $item['lead_id'],
                'counselor_user_id' => $item['user_id'],
                'counselor_name' => $item['counselor'],
                'course_id' => $item['course_id'],
                'course_code' => $item['course_code'],
                'course_name' => $item['course_name'],
                'category_code' => $item['category_code'],
                'bv' => $item['bv'],
                'admission_date' => $item['admission_date'],
                'initial_qualified_date' => $item['initial_qualified_date'],
                'full_payment_date' => $item['full_payment_date'],
                'gross_incentive_share' => $item['gross_incentive_share'],
                'initial_payable' => $item['initial_payable'],
                'balance_payable' => $item['balance_payable'],
                'balance_released' => $item['balance_released'],
                'row_status' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]);
        }

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->db->trans_rollback();
            return $this->error('RUN-E003', 'Unable to save the incentive draft.', $error['message']);
        }
        $this->db->trans_commit();

        return $this->success(['run_id' => $run_id]);
    }

    public function change_run_status($run_id, $target_status)
    {
        $target_status = strtoupper($target_status);
        $allowed = ['APPROVED' => 'DRAFT', 'PAID' => 'APPROVED'];
        if (empty($allowed[$target_status])) {
            return $this->error('STS-E001', 'Invalid incentive status transition.');
        }

        $run_id = (int) $run_id;
        $query = "SELECT * FROM $this->tb4
                  WHERE id = $run_id AND row_status = 1
                  LIMIT 1";
        $run = $this->db->query($query)->row_array();
        if (empty($run) || $run['status'] !== $allowed[$target_status]) {
            return $this->error('STS-E002', 'The incentive run is no longer in the required status. Refresh the page and try again.');
        }

        $user_id = (int) get_user()['id'];
        $data = ['status' => $target_status, 'updated_by' => $user_id];
        if ($target_status === 'APPROVED') {
            $data['approved_by'] = $user_id;
            $data['approved_at'] = date('Y-m-d H:i:s');
        } else {
            $data['paid_by'] = $user_id;
            $data['paid_at'] = date('Y-m-d H:i:s');
        }
        $this->update_row($this->tb4, $data, "id = $run_id");

        if ($this->db->affected_rows() < 1) {
            return $this->error('STS-E003', 'Unable to update the incentive run status.');
        }
        return $this->success(['run_id' => $run_id, 'status' => $target_status]);
    }

    private function calculate_period($period, $restrict_user_id = null)
    {
        $period_start = $period . '-01';
        $period_end = date('Y-m-t', strtotime($period_start));
        $plans = $this->plans();
        $current_plan = $this->plan_for_date($plans, $period_end);
        if (empty($current_plan)) {
            throw new RuntimeException('No active incentive plan covers the selected month.');
        }

        $students = $this->student_source();
        $receipts = $this->receipt_source();
        foreach ($students as $index => &$student) {
            $student['receipts'] = @$receipts[$student['student_number']] ?: [];
            $student['initial_qualified_date'] = null;
            $student['full_payment_date'] = null;
            $student['plan_id'] = null;
            $student['category_code'] = null;
            $student['bv'] = 0.0;
            $student['gross_incentive_share'] = 0.0;
            $student['initial_share'] = 0.0;
            $student['balance_share'] = 0.0;

            $received = 0.0;
            foreach ($student['receipts'] as $receipt) {
                $received += (float) $receipt['amount'];
                $receipt_plan = $this->plan_for_date($plans, $receipt['receipt_date']);
                if (empty($student['initial_qualified_date']) && !empty($receipt_plan)) {
                    $rule = $this->course_rule($receipt_plan, $student['course_code'], $student['course_name']);
                    if (!empty($rule) && $received + 0.0001 >= (float) $rule['initial_payment']) {
                        $student['initial_qualified_date'] = $receipt['receipt_date'];
                        $student['plan_id'] = (int) $receipt_plan['id'];
                        $student['category_code'] = $rule['category_code'];
                        $student['bv'] = (float) $rule['bv'];
                    }
                }
                if (empty($student['full_payment_date']) && $received + 0.0001 >= (float) $student['final_amount']) {
                    $student['full_payment_date'] = $receipt['receipt_date'];
                }
            }
            unset($student['receipts']);
        }
        unset($student);

        $cohorts = [];
        foreach ($students as $index => $student) {
            if (empty($student['initial_qualified_date']) || empty($student['plan_id'])) {
                continue;
            }
            $cohort_key = $student['plan_id'] . '|' . substr($student['initial_qualified_date'], 0, 7) . '|' . $student['user_id'];
            $cohorts[$cohort_key][] = $index;
        }

        foreach ($cohorts as $indexes) {
            $plan = $plans[$students[$indexes[0]]['plan_id']];
            $total_bv = 0.0;
            foreach ($indexes as $index) {
                $total_bv += $students[$index]['bv'];
            }
            $gross = $this->progressive_incentive($total_bv, $plan['slabs']);
            $initial_total = round($gross * ((float) $plan['initial_release_percent'] / 100), 2);
            $balance_total = round($gross - $initial_total, 2);
            $gross_allocations = $this->allocate_amount($gross, $indexes, $students, $total_bv);
            $initial_allocations = $this->allocate_amount($initial_total, $indexes, $students, $total_bv);
            $balance_allocations = $this->allocate_amount($balance_total, $indexes, $students, $total_bv);
            foreach ($indexes as $index) {
                $students[$index]['gross_incentive_share'] = $gross_allocations[$index];
                $students[$index]['initial_share'] = $initial_allocations[$index];
                $students[$index]['balance_share'] = $balance_allocations[$index];
            }
        }

        $snapshot_items = $this->approved_initial_snapshots();
        $released_students = $this->released_students();
        $rows = [];
        foreach ($this->counselors() as $counselor) {
            if ($restrict_user_id && (int) $counselor['id'] !== (int) $restrict_user_id) {
                continue;
            }
            $rows[(int) $counselor['id']] = $this->empty_row($counselor, $period_end, $current_plan['pay_day']);
        }

        $items = [];
        foreach ($cohorts as $indexes) {
            $first = $students[$indexes[0]];
            if (substr($first['initial_qualified_date'], 0, 7) !== $period) {
                continue;
            }
            $user_id = (int) $first['user_id'];
            if ($restrict_user_id && $user_id !== (int) $restrict_user_id) {
                continue;
            }
            if (!isset($rows[$user_id])) {
                $rows[$user_id] = $this->empty_row(['id' => $user_id, 'name' => $first['counselor']], $period_end, $current_plan['pay_day']);
            }
            $plan = $plans[$first['plan_id']];
            $cohort_bv = 0.0;
            foreach ($indexes as $index) {
                $student = $students[$index];
                $cohort_bv += $student['bv'];
                if ($student['category_code'] === 'BTECH') {
                    $rows[$user_id]['btech_admissions']++;
                } else {
                    $rows[$user_id]['other_admissions']++;
                }
                $items[$student['student_id']] = $this->report_item($student, $student['initial_share'], 0.0);
            }
            $cohort_gross = $this->progressive_incentive($cohort_bv, $plan['slabs']);
            $rows[$user_id]['total_bv'] += $cohort_bv;
            $rows[$user_id]['eligible_bv'] += max(0, $cohort_bv - (float) $plan['qualifying_bv']);
            $rows[$user_id]['gross_incentive'] += $cohort_gross;
            $rows[$user_id]['current_payable'] += round($cohort_gross * ((float) $plan['initial_release_percent'] / 100), 2);
        }

        foreach ($students as $student) {
            if (empty($student['full_payment_date']) || substr($student['full_payment_date'], 0, 7) !== $period) {
                continue;
            }
            if (isset($released_students[$student['student_id']])) {
                continue;
            }

            $source = @$snapshot_items[$student['student_id']];
            if (!empty($source)) {
                $balance = (float) $source['balance_payable'];
                $user_id = (int) $source['counselor_user_id'];
                $student['plan_id'] = (int) $source['plan_id'];
                $student['lead_id'] = (int) $source['lead_id'];
                $student['user_id'] = $user_id;
                $student['counselor'] = $source['counselor_name'];
                $student['course_id'] = (int) $source['course_id'];
                $student['course_code'] = $source['course_code'];
                $student['course_name'] = $source['course_name'];
                $student['category_code'] = $source['category_code'];
                $student['bv'] = (float) $source['bv'];
                $student['admission_date'] = $source['admission_date'];
                $student['initial_qualified_date'] = $source['initial_qualified_date'];
                $student['gross_incentive_share'] = (float) $source['gross_incentive_share'];
                $student['balance_share'] = $balance;
            } else {
                $balance = (float) $student['balance_share'];
                $user_id = (int) $student['user_id'];
            }
            if ($balance <= 0 || ($restrict_user_id && $user_id !== (int) $restrict_user_id)) {
                continue;
            }
            if (!isset($rows[$user_id])) {
                $rows[$user_id] = $this->empty_row(['id' => $user_id, 'name' => $student['counselor']], $period_end, $current_plan['pay_day']);
            }
            $rows[$user_id]['settled_students']++;
            $rows[$user_id]['balance_released'] += $balance;
            if (isset($items[$student['student_id']])) {
                $items[$student['student_id']]['balance_released'] = round($balance, 2);
                $items[$student['student_id']]['full_payment_date'] = $student['full_payment_date'];
            } else {
                $items[$student['student_id']] = $this->report_item($student, 0.0, $balance);
            }
        }

        foreach ($rows as &$row) {
            foreach (['total_bv', 'eligible_bv', 'gross_incentive', 'current_payable', 'balance_released'] as $field) {
                $row[$field] = round((float) $row[$field], 2);
            }
            $row['total_payable'] = round($row['current_payable'] + $row['balance_released'], 2);
        }
        unset($row);
        $rows = array_values($rows);
        usort($rows, function ($left, $right) {
            return strcasecmp($left['counselor'], $right['counselor']);
        });
        $items = array_values($items);
        usort($items, function ($left, $right) {
            $name_compare = strcasecmp($left['counselor'], $right['counselor']);
            return $name_compare ?: strcasecmp($left['student_number'], $right['student_number']);
        });

        return [
            'period' => ['value' => $period, 'start' => $period_start, 'end' => $period_end],
            'currency' => $current_plan['currency'],
            'run' => null,
            'source' => 'LIVE',
            'summary' => $this->summary($rows),
            'rows' => $rows,
            'items' => $items
        ];
    }

    private function snapshot_report(array $run, $restrict_user_id)
    {
        $run_id = (int) $run['id'];
        $scope = !empty($restrict_user_id)
            ? ' AND counselor_user_id = ' . (int) $restrict_user_id : '';
        $query = "SELECT * FROM $this->tb5
                  WHERE run_id = $run_id AND row_status = 1$scope
                  ORDER BY counselor_name ASC";
        $rows = $this->db->query($query)->result_array();
        foreach ($rows as &$row) {
            $row['user_id'] = (int) $row['counselor_user_id'];
            $row['counselor'] = $row['counselor_name'];
            $row['settled_students'] = 0;
            foreach (['btech_admissions', 'other_admissions'] as $field) {
                $row[$field] = (int) $row[$field];
            }
            foreach (['total_bv', 'eligible_bv', 'gross_incentive', 'current_payable', 'balance_released', 'total_payable'] as $field) {
                $row[$field] = (float) $row[$field];
            }
        }
        unset($row);

        $query = "SELECT * FROM $this->tb6
                  WHERE run_id = $run_id AND row_status = 1$scope
                  ORDER BY counselor_name ASC, student_number ASC";
        $items = $this->db->query($query)->result_array();
        $settled_students = [];
        foreach ($items as &$item) {
            $item['user_id'] = (int) $item['counselor_user_id'];
            $item['counselor'] = $item['counselor_name'];
            foreach (['bv', 'gross_incentive_share', 'initial_payable', 'balance_payable', 'balance_released'] as $field) {
                $item[$field] = (float) $item[$field];
            }
            if ($item['balance_released'] > 0) {
                $settled_students[$item['user_id']][(int) $item['student_id']] = true;
            }
        }
        unset($item);
        foreach ($rows as &$row) {
            $row['settled_students'] = isset($settled_students[$row['user_id']])
                ? count($settled_students[$row['user_id']]) : 0;
        }
        unset($row);

        return [
            'period' => ['value' => substr($run['period_start'], 0, 7), 'start' => $run['period_start'], 'end' => $run['period_end']],
            'currency' => $run['currency'],
            'run' => [
                'id' => (int) $run['id'],
                'status' => $run['status'],
                'generated_at' => $run['generated_at'],
                'approved_at' => $run['approved_at'],
                'paid_at' => $run['paid_at']
            ],
            'source' => 'SNAPSHOT',
            'summary' => $this->summary($rows),
            'rows' => $rows,
            'items' => $items
        ];
    }

    private function plans()
    {
        $plans = [];
        $query = "SELECT * FROM $this->tb1
                  WHERE row_status = 1 AND status = 'ACTIVE'
                  ORDER BY effective_from ASC";
        $rows = $this->db->query($query)->result_array();
        foreach ($rows as $row) {
            $plan_id = (int) $row['id'];

            $query = "SELECT * FROM $this->tb2
                      WHERE plan_id = $plan_id AND row_status = 1
                      ORDER BY priority ASC";
            $row['rules'] = $this->db->query($query)->result_array();

            $query = "SELECT * FROM $this->tb3
                      WHERE plan_id = $plan_id AND row_status = 1
                      ORDER BY slab_order ASC";
            $row['slabs'] = $this->db->query($query)->result_array();
            $plans[$plan_id] = $row;
        }

        return $plans;
    }

    private function plan_for_date(array $plans, $date)
    {
        $selected = null;
        foreach ($plans as $plan) {
            if ($date >= $plan['effective_from'] && (empty($plan['effective_to']) || $date <= $plan['effective_to'])) {
                $selected = $plan;
            }
        }

        return $selected;
    }

    private function student_source()
    {
        $query = "SELECT s.id student_id, s.student_number,
                         DATE(s.created_at) admission_date,
                         l.id lead_id, l.assigned_to user_id,
                         TRIM(up.name) counselor,
                         uc.id course_id, uc.course_code, uc.course_name,
                         p.final_amount
                  FROM students s
                  INNER JOIN (
                      SELECT enquiry_number, MAX(id) lead_id
                      FROM leads
                      WHERE row_status = 1
                      GROUP BY enquiry_number
                  ) latest_lead
                    ON latest_lead.enquiry_number = s.enquiry_number
                  INNER JOIN leads l
                    ON l.id = latest_lead.lead_id
                  INNER JOIN users u
                    ON u.id = l.assigned_to
                    AND u.row_status = 1
                    AND JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_MKTST'))
                    AND NOT JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_ADMIN'))
                  INNER JOIN user_profiles up
                    ON up.user_id = u.id
                    AND up.row_status = 1
                    AND NULLIF(TRIM(up.name), '') IS NOT NULL
                  INNER JOIN university_courses uc
                    ON uc.id = s.course_id AND uc.row_status = 1
                  INNER JOIN (
                      SELECT payment.student_number, payment.final_amount
                      FROM payments payment
                      INNER JOIN (
                          SELECT student_number, MAX(id) payment_id
                          FROM payments
                          WHERE row_status = 1
                          GROUP BY student_number
                      ) latest_payment
                        ON latest_payment.payment_id = payment.id
                  ) p
                    ON p.student_number = s.student_number
                  WHERE s.row_status = 1";

        return $this->db->query($query)->result_array();
    }

    private function receipt_source()
    {
        $query = "SELECT student_number, receipt_date, amount, id
                  FROM payment_receipts
                  WHERE row_status = 1
                  ORDER BY student_number ASC, receipt_date ASC, id ASC";
        $rows = $this->db->query($query)->result_array();
        $output = [];
        foreach ($rows as $row) {
            $output[$row['student_number']][] = $row;
        }

        return $output;
    }

    private function course_rule(array $plan, $course_code, $course_name)
    {
        $haystack = $this->normalise_keyword($course_code . ' ' . $course_name);
        $fallback = null;
        foreach ($plan['rules'] as $rule) {
            if ($rule['category_code'] === 'OTHER') {
                $fallback = $rule;
                continue;
            }
            $keyword = $this->normalise_keyword($rule['match_keyword']);
            if ($keyword !== '' && strpos($haystack, $keyword) !== false) {
                return $rule;
            }
        }
        return $fallback;
    }

    private function progressive_incentive($total_bv, array $slabs)
    {
        $incentive = 0.0;
        foreach ($slabs as $slab) {
            $from = (float) $slab['from_bv'];
            $to = $slab['to_bv'] === null ? $total_bv : min($total_bv, (float) $slab['to_bv']);
            $units = max(0, $to - $from + 1);
            $incentive += $units * (float) $slab['rate_per_bv'];
        }
        return round($incentive, 2);
    }

    private function allocate_amount($amount, array $indexes, array $students, $total_bv)
    {
        $allocations = [];
        $allocated = 0.0;
        $last_position = count($indexes) - 1;
        foreach ($indexes as $position => $index) {
            if ($position === $last_position) {
                $share = round($amount - $allocated, 2);
            } else {
                $share = $total_bv > 0 ? round($amount * ((float) $students[$index]['bv'] / $total_bv), 2) : 0.0;
                $allocated += $share;
            }
            $allocations[$index] = $share;
        }
        return $allocations;
    }

    private function approved_initial_snapshots()
    {
        $query = "SELECT item.*
                  FROM $this->tb6 item
                  INNER JOIN $this->tb4 run
                    ON run.id = item.run_id AND run.row_status = 1
                  WHERE run.status IN ('APPROVED', 'PAID')
                    AND item.row_status = 1
                    AND item.initial_payable > 0
                  ORDER BY run.period_start ASC";
        $rows = $this->db->query($query)->result_array();
        $output = [];
        foreach ($rows as $row) {
            if (!isset($output[$row['student_id']])) {
                $output[$row['student_id']] = $row;
            }
        }

        return $output;
    }

    private function released_students()
    {
        $query = "SELECT item.student_id
                  FROM $this->tb6 item
                  INNER JOIN $this->tb4 run
                    ON run.id = item.run_id AND run.row_status = 1
                  WHERE run.status IN ('APPROVED', 'PAID')
                    AND item.row_status = 1
                    AND item.balance_released > 0";
        $rows = $this->db->query($query)->result_array();
        $output = [];
        foreach ($rows as $row) {
            $output[$row['student_id']] = true;
        }

        return $output;
    }

    private function report_item(array $student, $initial_payable, $balance_released)
    {
        return [
            'plan_id' => (int) $student['plan_id'],
            'student_id' => (int) $student['student_id'],
            'student_number' => $student['student_number'],
            'lead_id' => (int) $student['lead_id'],
            'user_id' => (int) $student['user_id'],
            'counselor' => $student['counselor'],
            'course_id' => (int) $student['course_id'],
            'course_code' => $student['course_code'],
            'course_name' => $student['course_name'],
            'category_code' => $student['category_code'],
            'bv' => round((float) $student['bv'], 2),
            'admission_date' => $student['admission_date'],
            'initial_qualified_date' => $student['initial_qualified_date'],
            'full_payment_date' => $student['full_payment_date'],
            'gross_incentive_share' => round((float) $student['gross_incentive_share'], 2),
            'initial_payable' => round((float) $initial_payable, 2),
            'balance_payable' => round((float) $student['balance_share'], 2),
            'balance_released' => round((float) $balance_released, 2)
        ];
    }

    private function empty_row(array $counselor, $period_end, $pay_day)
    {
        $next_month = date('Y-m-01', strtotime($period_end . ' +1 day'));
        return [
            'user_id' => (int) $counselor['id'],
            'counselor' => $counselor['name'],
            'btech_admissions' => 0,
            'other_admissions' => 0,
            'total_bv' => 0.0,
            'eligible_bv' => 0.0,
            'gross_incentive' => 0.0,
            'current_payable' => 0.0,
            'settled_students' => 0,
            'balance_released' => 0.0,
            'total_payable' => 0.0,
            'pay_date' => date('Y-m-d', strtotime($next_month . ' +' . ((int) $pay_day - 1) . ' days'))
        ];
    }

    private function summary(array $rows)
    {
        $summary = [
            'btech_admissions' => 0,
            'other_admissions' => 0,
            'total_bv' => 0.0,
            'eligible_bv' => 0.0,
            'gross_incentive' => 0.0,
            'current_payable' => 0.0,
            'settled_students' => 0,
            'balance_released' => 0.0,
            'total_payable' => 0.0
        ];
        foreach ($rows as $row) {
            foreach ($summary as $field => $value) {
                $summary[$field] += (float) @$row[$field];
            }
        }
        $summary['btech_admissions'] = (int) $summary['btech_admissions'];
        $summary['other_admissions'] = (int) $summary['other_admissions'];
        $summary['settled_students'] = (int) $summary['settled_students'];
        foreach (['total_bv', 'eligible_bv', 'gross_incentive', 'current_payable', 'balance_released', 'total_payable'] as $field) {
            $summary[$field] = round($summary[$field], 2);
        }

        return $summary;
    }

    private function run_by_period($period)
    {
        $period_start = $this->db->escape($period . '-01');
        $query = "SELECT * FROM $this->tb4
                  WHERE period_start = $period_start AND row_status = 1
                  LIMIT 1";

        return $this->db->query($query)->row_array();
    }

    private function plan_is_locked($plan_id)
    {
        $plan_id = (int) $plan_id;
        $query = "SELECT COUNT(*) total
                  FROM $this->tb6 item
                  INNER JOIN $this->tb4 run
                    ON run.id = item.run_id AND run.row_status = 1
                  WHERE item.plan_id = $plan_id
                    AND item.row_status = 1
                    AND run.status IN ('APPROVED', 'PAID')";
        $result = $this->db->query($query)->row_array();

        return (int) @$result['total'] > 0;
    }

    private function rule_by_code(array $rules, $code)
    {
        foreach ($rules as $rule) {
            if ($rule['category_code'] === $code) {
                return $rule;
            }
        }

        return [];
    }

    private function normalise_keyword($value)
    {
        return strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $value));
    }

    private function insert_row($table, array $datas)
    {
        $columns = [];
        $values = [];
        foreach ($datas as $column => $value) {
            $columns[] = "`$column`";
            $values[] = $this->db->escape($value);
        }

        $query = "INSERT INTO $table (" . implode(', ', $columns) . ')'
            . ' VALUES (' . implode(', ', $values) . ')';

        return $this->db->query($query);
    }

    private function update_row($table, array $datas, $whereclause)
    {
        $values = [];
        foreach ($datas as $column => $value) {
            $values[] = "`$column` = " . $this->db->escape($value);
        }

        $query = "UPDATE $table SET " . implode(', ', $values) . " WHERE $whereclause";

        return $this->db->query($query);
    }

    private function success(array $data)
    {
        return [
            'status' => TRUE,
            'code' => null,
            'replace_code_value' => null,
            'redirectUrl' => null,
            'debug' => null,
            'data' => $data
        ];
    }

    private function error($suffix, $message, $debug = null)
    {
        return [
            'status' => FALSE,
            'code' => $this->error_prefix . '-' . $suffix,
            'replace_code_value' => ['message' => $message],
            'redirectUrl' => null,
            'debug' => $debug ? ['file' => __FILE__, 'line' => __LINE__, 'hint' => $debug] : null,
            'data' => null
        ];
    }
}
