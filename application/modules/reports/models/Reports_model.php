<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends CI_Model
{
    public function counseling_admissions_cache_version()
    {
        $row = $this->db->query(
            "SELECT
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM leads) leads_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM lead_contact_logs) contacts_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM students) students_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM payments) payments_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM payment_receipts) receipts_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM users) users_version,
                (SELECT CONCAT(COUNT(*), ':', COALESCE(UNIX_TIMESTAMP(MAX(updated_at)), 0)) FROM user_profiles) profiles_version"
        )->row_array();

        return hash('sha256', implode('|', $row ?: []));
    }

    public function counseling_admissions_report($report_date, $month_start, $month_end, $restrict_assigned_to = null)
    {
        $daily = $this->counseling_period_metrics($report_date, $report_date, $restrict_assigned_to);
        $monthly = $this->counseling_period_metrics($month_start, $month_end, $restrict_assigned_to);
        $admission = $this->admission_financial_metrics(
            $month_start,
            $month_end,
            $report_date,
            $restrict_assigned_to,
            $monthly['rows']
        );

        return [
            'daily' => $daily,
            'monthly' => $monthly,
            'admission' => $admission,
        ];
    }

    private function counseling_period_metrics($start_date, $end_date, $restrict_assigned_to = null)
    {
        $scope = $restrict_assigned_to ? ' AND l.assigned_to = ' . (int) $restrict_assigned_to : '';
        $start = $this->db->escape($start_date . ' 00:00:00');
        $end_exclusive = $this->db->escape(date('Y-m-d', strtotime($end_date . ' +1 day')) . ' 00:00:00');

        $lead_rows = $this->db->query(
            "SELECT l.assigned_to user_id, TRIM(up.name) counselor,
                    COUNT(*) total_leads
             FROM leads l
             INNER JOIN users u ON u.id = l.assigned_to AND u.row_status = 1
             INNER JOIN user_profiles up ON up.user_id = u.id AND NULLIF(TRIM(up.name), '') IS NOT NULL
             WHERE l.row_status = 1
               AND l.created_at >= $start AND l.created_at < $end_exclusive$scope
             GROUP BY l.assigned_to, TRIM(up.name)"
        )->result_array();

        $contact_scope = $restrict_assigned_to
            ? ' AND latest_contact.counselor_user_id = ' . (int) $restrict_assigned_to
            : '';
        $contact_rows = $this->db->query(
            "SELECT latest_contact.counselor_user_id user_id, TRIM(up.name) counselor,
                    COUNT(*) leads_called,
                    SUM(CASE WHEN log.contact_result = 'RESPONDED' THEN 1 ELSE 0 END) responded,
                    SUM(CASE WHEN log.contact_result = 'NO_RESPONSE' THEN 1 ELSE 0 END) no_response
             FROM (
                 SELECT counselor_user_id, lead_id, MAX(id) contact_log_id
                 FROM lead_contact_logs
                 WHERE row_status = 1 AND contact_channel = 'CALL'
                   AND created_at >= $start AND created_at < $end_exclusive
                 GROUP BY counselor_user_id, lead_id
             ) latest_contact
             INNER JOIN lead_contact_logs log ON log.id = latest_contact.contact_log_id
             INNER JOIN users u ON u.id = latest_contact.counselor_user_id AND u.row_status = 1
             INNER JOIN user_profiles up ON up.user_id = u.id AND NULLIF(TRIM(up.name), '') IS NOT NULL
             WHERE 1 = 1$contact_scope
             GROUP BY latest_contact.counselor_user_id, TRIM(up.name)"
        )->result_array();

        $admission_rows = $this->db->query(
            "SELECT l.assigned_to user_id, TRIM(up.name) counselor, COUNT(DISTINCT s.id) admissions
             FROM students s
             INNER JOIN (
                 SELECT enquiry_number, MAX(id) lead_id
                 FROM leads WHERE row_status = 1 GROUP BY enquiry_number
             ) latest_lead ON latest_lead.enquiry_number = s.enquiry_number
             INNER JOIN leads l ON l.id = latest_lead.lead_id
             INNER JOIN users u ON u.id = l.assigned_to AND u.row_status = 1
             INNER JOIN user_profiles up ON up.user_id = u.id AND NULLIF(TRIM(up.name), '') IS NOT NULL
             WHERE s.row_status = 1
               AND s.created_at >= $start AND s.created_at < $end_exclusive$scope
             GROUP BY l.assigned_to, TRIM(up.name)"
        )->result_array();

        $rows = [];
        foreach ($lead_rows as $row) {
            $user_id = (int) $row['user_id'];
            $rows[$user_id] = [
                'user_id' => $user_id,
                'counselor' => $row['counselor'],
                'total_leads' => (int) $row['total_leads'],
                'leads_called' => 0,
                'responded' => 0,
                'no_response' => 0,
                'admissions' => 0,
            ];
        }
        foreach ($contact_rows as $row) {
            $user_id = (int) $row['user_id'];
            if (!isset($rows[$user_id])) {
                $rows[$user_id] = [
                    'user_id' => $user_id,
                    'counselor' => $row['counselor'],
                    'total_leads' => 0,
                    'leads_called' => 0,
                    'responded' => 0,
                    'no_response' => 0,
                    'admissions' => 0,
                ];
            }
            $rows[$user_id]['leads_called'] = (int) $row['leads_called'];
            $rows[$user_id]['responded'] = (int) $row['responded'];
            $rows[$user_id]['no_response'] = (int) $row['no_response'];
        }
        foreach ($admission_rows as $row) {
            $user_id = (int) $row['user_id'];
            if (!isset($rows[$user_id])) {
                $rows[$user_id] = [
                    'user_id' => $user_id,
                    'counselor' => $row['counselor'],
                    'total_leads' => 0,
                    'leads_called' => 0,
                    'responded' => 0,
                    'no_response' => 0,
                    'admissions' => 0,
                ];
            }
            $rows[$user_id]['admissions'] = (int) $row['admissions'];
        }

        $rows = array_values($rows);
        usort($rows, function ($left, $right) {
            return strcasecmp($left['counselor'], $right['counselor']);
        });

        $totals = $this->sum_report_rows($rows, [
            'total_leads', 'leads_called', 'responded', 'no_response', 'admissions'
        ]);
        return ['rows' => $rows, 'totals' => $totals];
    }

    private function admission_financial_metrics($start_date, $end_date, $report_date, $restrict_assigned_to, array $monthly_rows)
    {
        $scope = $restrict_assigned_to ? ' AND l.assigned_to = ' . (int) $restrict_assigned_to : '';
        $start = $this->db->escape($start_date . ' 00:00:00');
        $end_exclusive = $this->db->escape(date('Y-m-d', strtotime($end_date . ' +1 day')) . ' 00:00:00');
        $receipt_cutoff = $this->db->escape($report_date);

        $rows = $this->db->query(
            "SELECT l.assigned_to user_id, TRIM(up.name) counselor,
                    COUNT(DISTINCT s.id) total_admissions,
                    COALESCE(SUM(COALESCE(pay.final_amount, s.final_fees, 0)), 0) total_fees,
                    COALESCE(SUM(LEAST(COALESCE(receipt.received, 0), COALESCE(pay.final_amount, s.final_fees, 0))), 0) advance_received,
                    COALESCE(SUM(GREATEST(COALESCE(pay.final_amount, s.final_fees, 0) - COALESCE(receipt.received, 0), 0)), 0) balance_receivable
             FROM students s
             INNER JOIN (
                 SELECT enquiry_number, MAX(id) lead_id
                 FROM leads WHERE row_status = 1 GROUP BY enquiry_number
             ) latest_lead ON latest_lead.enquiry_number = s.enquiry_number
             INNER JOIN leads l ON l.id = latest_lead.lead_id
             INNER JOIN users u ON u.id = l.assigned_to AND u.row_status = 1
             INNER JOIN user_profiles up ON up.user_id = u.id AND NULLIF(TRIM(up.name), '') IS NOT NULL
             LEFT JOIN (
                 SELECT p.student_number, p.final_amount
                 FROM payments p
                 INNER JOIN (
                     SELECT student_number, MAX(id) payment_id
                     FROM payments WHERE row_status = 1 GROUP BY student_number
                 ) latest_payment ON latest_payment.payment_id = p.id
             ) pay ON pay.student_number = s.student_number
             LEFT JOIN (
                 SELECT student_number, SUM(amount) received
                 FROM payment_receipts
                 WHERE row_status = 1 AND receipt_date <= $receipt_cutoff
                 GROUP BY student_number
             ) receipt ON receipt.student_number = s.student_number
             WHERE s.row_status = 1
               AND s.created_at >= $start AND s.created_at < $end_exclusive$scope
             GROUP BY l.assigned_to, TRIM(up.name)"
        )->result_array();

        $output = [];
        $included_user_ids = [];
        foreach ($rows as $row) {
            $user_id = (int) $row['user_id'];
            $included_user_ids[$user_id] = true;
            $output[] = [
                'user_id' => $user_id,
                'counselor' => $row['counselor'],
                'total_admissions' => (int) $row['total_admissions'],
                'total_fees' => (float) $row['total_fees'],
                'advance_received' => (float) $row['advance_received'],
                'balance_receivable' => (float) $row['balance_receivable'],
            ];
        }

        foreach ($monthly_rows as $monthly_row) {
            $user_id = (int) $monthly_row['user_id'];
            if (isset($included_user_ids[$user_id])) {
                continue;
            }
            $output[] = [
                'user_id' => $user_id,
                'counselor' => $monthly_row['counselor'],
                'total_admissions' => 0,
                'total_fees' => 0.0,
                'advance_received' => 0.0,
                'balance_receivable' => 0.0,
            ];
        }

        usort($output, function ($left, $right) {
            return strcasecmp($left['counselor'], $right['counselor']);
        });

        return ['rows' => $output, 'totals' => $this->sum_report_rows($output, [
            'total_admissions', 'total_fees', 'advance_received', 'balance_receivable'
        ])];
    }

    private function sum_report_rows(array $rows, array $columns)
    {
        $totals = array_fill_keys($columns, 0);
        foreach ($rows as $row) {
            foreach ($columns as $column) {
                $totals[$column] += $row[$column] ?? 0;
            }
        }
        return $totals;
    }

    public function lead_filters()
    {
        return [
            'sources' => $this->db->select('source_code, source_name')
                ->from('leads_sources')->where('row_status', 1)
                ->order_by('source_name', 'ASC')->get()->result_array(),
            'universities' => $this->db->select('id, university_name')
                ->from('universities')->where('row_status', 1)
                ->order_by('university_name', 'ASC')->get()->result_array(),
            'counselors' => $this->db->select('u.id, p.name')
                ->from('users u')->join('user_profiles p', 'p.user_id = u.id')
                ->where('u.row_status', 1)->where("NULLIF(TRIM(p.name), '') IS NOT NULL", null, false)
                ->order_by('p.name', 'ASC')->get()->result_array(),
        ];
    }

    public function leads_overview(array $filters = [])
    {
        $where = $this->lead_where($filters);
        $summary = $this->db->query(
            "SELECT COUNT(*) total,
                    SUM(UPPER(status) = 'YES') converted,
                    SUM(UPPER(status) = 'PENDING') pending,
                    SUM(UPPER(status) = 'NO') lost,
                    SUM(UPPER(status) = 'PENDING' AND follow_up_date < CURDATE()) overdue
             FROM leads l WHERE $where"
        )->row_array();

        $total = (int) ($summary['total'] ?? 0);
        $summary = [
            'total' => $total,
            'converted' => (int) ($summary['converted'] ?? 0),
            'pending' => (int) ($summary['pending'] ?? 0),
            'lost' => (int) ($summary['lost'] ?? 0),
            'overdue' => (int) ($summary['overdue'] ?? 0),
            'conversion_rate' => $total > 0
                ? round(((int) $summary['converted'] / $total) * 100, 1)
                : 0,
        ];

        $bounds = $this->db->query(
            "SELECT MIN(DATE(l.created_at)) min_date, MAX(DATE(l.created_at)) max_date
             FROM leads l WHERE $where"
        )->row_array();
        $grouping = $this->trend_grouping($bounds['min_date'] ?? null, $bounds['max_date'] ?? null);

        $trend_rows = $this->db->query(
            "SELECT {$grouping['select']} period_key,
                    {$grouping['label']} period_label,
                    COALESCE(ls.source_name, NULLIF(l.source_code, ''), 'Unknown') source_name,
                    COUNT(*) total
             FROM leads l
             LEFT JOIN leads_sources ls ON ls.source_code = l.source_code AND ls.row_status = 1
             WHERE $where
             GROUP BY {$grouping['select']}, {$grouping['label']},
                      COALESCE(ls.source_name, NULLIF(l.source_code, ''), 'Unknown')
             ORDER BY period_key ASC, source_name ASC"
        )->result_array();

        $trend = $this->format_trend($trend_rows);
        $status = $this->db->query(
            "SELECT COALESCE(NULLIF(UPPER(l.status), ''), 'UNKNOWN') name, COUNT(*) value
             FROM leads l WHERE $where
             GROUP BY COALESCE(NULLIF(UPPER(l.status), ''), 'UNKNOWN') ORDER BY value DESC"
        )->result_array();
        $top_sources = $this->db->query(
            "SELECT COALESCE(ls.source_name, NULLIF(l.source_code, ''), 'Unknown') name,
                    COUNT(*) total,
                    SUM(UPPER(l.status) = 'YES') converted
             FROM leads l
             LEFT JOIN leads_sources ls ON ls.source_code = l.source_code AND ls.row_status = 1
             WHERE $where
             GROUP BY COALESCE(ls.source_name, NULLIF(l.source_code, ''), 'Unknown')
             ORDER BY total DESC, name ASC LIMIT 8"
        )->result_array();
        $top_counselors = $this->db->query(
            "SELECT COALESCE(NULLIF(TRIM(p.name), ''), 'Unassigned') name,
                    COUNT(*) total,
                    SUM(UPPER(l.status) = 'YES') converted,
                    SUM(UPPER(l.status) = 'PENDING' AND l.follow_up_date < CURDATE()) overdue
             FROM leads l LEFT JOIN users u ON u.id = l.assigned_to AND u.row_status = 1
             LEFT JOIN user_profiles p ON p.user_id = u.id
             WHERE $where
             GROUP BY COALESCE(NULLIF(TRIM(p.name), ''), 'Unassigned')
             ORDER BY converted DESC, total DESC, name ASC LIMIT 8"
        )->result_array();

        foreach ($top_sources as &$row) {
            $row['total'] = (int) $row['total'];
            $row['converted'] = (int) $row['converted'];
            $row['conversion_rate'] = $row['total'] > 0
                ? round(($row['converted'] / $row['total']) * 100, 1)
                : 0;
        }
        unset($row);
        foreach ($top_counselors as &$row) {
            $row['total'] = (int) $row['total'];
            $row['converted'] = (int) $row['converted'];
            $row['overdue'] = (int) $row['overdue'];
            $row['conversion_rate'] = $row['total'] > 0
                ? round(($row['converted'] / $row['total']) * 100, 1)
                : 0;
        }
        unset($row);

        return [
            'summary' => $summary,
            'trend' => $trend,
            'status' => array_map(function ($row) {
                return ['name' => $row['name'], 'value' => (int) $row['value']];
            }, $status),
            'top_sources' => $top_sources,
            'top_counselors' => $top_counselors,
            'period' => [
                'start' => $bounds['min_date'] ?? null,
                'end' => $bounds['max_date'] ?? null,
                'grouping' => $grouping['name'],
            ],
        ];
    }

    public function counselor_overview(array $filters = [])
    {
        $where = $this->lead_where($filters);
        $summaryRow = $this->db->query(
            "SELECT COUNT(*) total, SUM(UPPER(l.status)='YES') converted,
                    SUM(UPPER(l.status)='PENDING') pending, SUM(UPPER(l.status)='NO') lost,
                    SUM(UPPER(l.status)='PENDING' AND l.follow_up_date<CURDATE()) overdue,
                    COUNT(DISTINCT p.user_id) active_counselors
             FROM leads l LEFT JOIN users u ON u.id=l.assigned_to AND u.row_status=1
             LEFT JOIN user_profiles p ON p.user_id=u.id WHERE $where"
        )->row_array();
        $total = (int) ($summaryRow['total'] ?? 0);
        $summary = [
            'total' => $total,
            'converted' => (int) ($summaryRow['converted'] ?? 0),
            'pending' => (int) ($summaryRow['pending'] ?? 0),
            'lost' => (int) ($summaryRow['lost'] ?? 0),
            'overdue' => (int) ($summaryRow['overdue'] ?? 0),
            'active_counselors' => (int) ($summaryRow['active_counselors'] ?? 0),
            'conversion_rate' => $total ? round(((int) $summaryRow['converted'] / $total) * 100, 1) : 0,
        ];

        $trendRows = $this->db->query(
            "SELECT DATE_FORMAT(l.created_at,'%Y-%m') period_key,
                    DATE_FORMAT(l.created_at,'%b %Y') period_label,
                    SUM(UPPER(l.status)='YES') converted,
                    SUM(UPPER(l.status)='PENDING') pending,
                    SUM(UPPER(l.status)='NO') lost, COUNT(*) total
             FROM leads l WHERE $where
             GROUP BY DATE_FORMAT(l.created_at,'%Y-%m'),DATE_FORMAT(l.created_at,'%b %Y')
             ORDER BY period_key"
        )->result_array();
        $trend = ['labels'=>[],'total'=>[],'converted'=>[],'pending'=>[],'lost'=>[]];
        foreach ($trendRows as $row) {
            $trend['labels'][] = $row['period_label'];
            foreach (['total','converted','pending','lost'] as $key) $trend[$key][] = (int)$row[$key];
        }

        $performance = $this->db->query(
            "SELECT l.assigned_to user_id,
                    COALESCE(NULLIF(TRIM(p.name),''),'Unassigned') name,
                    COUNT(*) total, SUM(UPPER(l.status)='YES') converted,
                    SUM(UPPER(l.status)='PENDING') pending,
                    SUM(UPPER(l.status)='NO') lost,
                    SUM(UPPER(l.status)='PENDING' AND l.follow_up_date<CURDATE()) overdue
             FROM leads l LEFT JOIN users u ON u.id=l.assigned_to AND u.row_status=1
             LEFT JOIN user_profiles p ON p.user_id=u.id WHERE $where
             GROUP BY l.assigned_to,COALESCE(NULLIF(TRIM(p.name),''),'Unassigned')
             ORDER BY converted DESC,total DESC,name ASC"
        )->result_array();
        foreach ($performance as &$row) {
            foreach (['user_id','total','converted','pending','lost','overdue'] as $key) $row[$key] = (int) $row[$key];
            $row['conversion_rate'] = $row['total'] ? round(($row['converted'] / $row['total']) * 100, 1) : 0;
            $row['pending_rate'] = $row['total'] ? round(($row['pending'] / $row['total']) * 100, 1) : 0;
            $row['lost_rate'] = $row['total'] ? round(($row['lost'] / $row['total']) * 100, 1) : 0;
            $row['overdue_rate'] = $row['pending'] ? round(($row['overdue'] / $row['pending']) * 100, 1) : 0;
        }
        unset($row);

        $sources = $this->db->query(
            "SELECT COALESCE(ls.source_name,NULLIF(l.source_code,''),'Unknown') name,COUNT(*) value
             FROM leads l LEFT JOIN leads_sources ls ON ls.source_code=l.source_code AND ls.row_status=1
             WHERE $where
             GROUP BY COALESCE(ls.source_name,NULLIF(l.source_code,''),'Unknown') ORDER BY value DESC"
        )->result_array();
        foreach ($sources as &$source) $source['value'] = (int)$source['value'];
        unset($source);

        $bounds = $this->db->query("SELECT MIN(DATE(l.created_at)) start_date,MAX(DATE(l.created_at)) end_date FROM leads l WHERE $where")->row_array();
        return [
            'summary' => $summary,
            'trend' => $trend,
            'performance' => $performance,
            'sources' => $sources,
            'period' => ['start' => $bounds['start_date'] ?? null, 'end' => $bounds['end_date'] ?? null],
        ];
    }

    public function payment_receipt_overview(array $filters = [])
    {
        $where = $this->payment_where($filters);
        $base = "FROM students s
            LEFT JOIN leads l ON l.enquiry_number=s.enquiry_number
            LEFT JOIN payments p ON p.student_number=s.student_number AND p.row_status=1
            LEFT JOIN payment_invoices i ON i.student_number=s.student_number AND i.row_status=1
            $where";
        $summaryRow = $this->db->query("SELECT COUNT(DISTINCT s.id) students,
            SUM(p.id IS NOT NULL) payment_plans,
            SUM(i.id IS NULL) no_invoice,
            SUM(p.id IS NOT NULL AND (UPPER(p.status)='PAID' OR COALESCE(p.remaining_balance,0)<=0)) paid,
            SUM(p.id IS NOT NULL AND UPPER(p.status)='PARTIAL' AND COALESCE(p.remaining_balance,0)>0) partial,
            SUM(p.id IS NOT NULL AND UPPER(p.status)='UNPAID' AND COALESCE(p.remaining_balance,0)>0) unpaid,
            SUM(p.id IS NOT NULL AND COALESCE(p.remaining_balance,0)>0 AND p.due_date<CURDATE()) overdue,
            SUM(p.id IS NOT NULL AND COALESCE(p.remaining_balance,0)>0 AND p.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(),INTERVAL 7 DAY)) due_soon,
            COALESCE(SUM(p.final_amount),0) billed,
            COALESCE(SUM(p.final_amount-COALESCE(p.remaining_balance,0)),0) collected,
            COALESCE(SUM(p.remaining_balance),0) outstanding $base")->row_array();
        $receiptRow = $this->db->query("SELECT COALESCE(SUM(r.amount),0) receipt_total
            FROM payment_receipts r INNER JOIN students s ON s.student_number=r.student_number
            LEFT JOIN leads l ON l.enquiry_number=s.enquiry_number
            WHERE r.row_status=1" . $this->payment_scope_suffix($filters))->row_array();
        $summary = [];
        foreach (['students','payment_plans','no_invoice','paid','partial','unpaid','overdue','due_soon'] as $key) $summary[$key]=(int)($summaryRow[$key]??0);
        foreach (['billed','collected','outstanding'] as $key) $summary[$key]=(float)($summaryRow[$key]??0);
        $summary['receipt_total']=(float)($receiptRow['receipt_total']??0);
        $summary['collection_rate']=$summary['billed']>0?round(($summary['collected']/$summary['billed'])*100,1):0;

        $status = [
            ['name'=>'Paid','value'=>$summary['paid'],'itemStyle'=>['color'=>'#28a745']],
            ['name'=>'Partial','value'=>$summary['partial'],'itemStyle'=>['color'=>'#ffc107']],
            ['name'=>'Unpaid','value'=>$summary['unpaid'],'itemStyle'=>['color'=>'#dc3545']],
            ['name'=>'No Invoice','value'=>$summary['no_invoice'],'itemStyle'=>['color'=>'#6c757d']],
        ];
        $trendRows = $this->db->query("SELECT DATE_FORMAT(p.created_at,'%Y-%m') period_key,DATE_FORMAT(p.created_at,'%b %Y') label,
            SUM(p.final_amount) billed,SUM(p.final_amount-COALESCE(p.remaining_balance,0)) collected
            FROM students s LEFT JOIN leads l ON l.enquiry_number=s.enquiry_number
            INNER JOIN payments p ON p.student_number=s.student_number AND p.row_status=1
            $where GROUP BY DATE_FORMAT(p.created_at,'%Y-%m'),DATE_FORMAT(p.created_at,'%b %Y') ORDER BY period_key")->result_array();
        $trend=['labels'=>[],'billed'=>[],'collected'=>[]];
        foreach($trendRows as $row){$trend['labels'][]=$row['label'];$trend['billed'][]=(float)$row['billed'];$trend['collected'][]=(float)$row['collected'];}

        return ['summary'=>$summary,'status'=>$status,'trend'=>$trend];
    }

    public function payment_priority_datatable(array $filters, array $datatable)
    {
        $where=$this->payment_where($filters);
        $from="FROM students s LEFT JOIN leads l ON l.enquiry_number=s.enquiry_number
            LEFT JOIN payments p ON p.student_number=s.student_number AND p.row_status=1
            LEFT JOIN payment_invoices i ON i.student_number=s.student_number AND i.row_status=1";
        $search=trim($datatable['search']['value']??'');
        if($search!==''){
            $escaped=$this->db->escape_like_str($search);
            $where.=" AND (s.student_number LIKE '%$escaped%' ESCAPE '!' OR s.full_name LIKE '%$escaped%' ESCAPE '!' OR p.invoice_number LIKE '%$escaped%' ESCAPE '!')";
        }
        $state="CASE WHEN i.id IS NULL THEN 'NO_INVOICE'
            WHEN COALESCE(p.remaining_balance,0)<=0 OR UPPER(p.status)='PAID' THEN 'PAID'
            WHEN p.due_date<CURDATE() THEN 'OVERDUE'
            WHEN p.due_date<=DATE_ADD(CURDATE(),INTERVAL 7 DAY) THEN 'DUE_SOON' ELSE 'OUTSTANDING' END";
        $stateRank="CASE $state WHEN 'OVERDUE' THEN 1 WHEN 'DUE_SOON' THEN 2 WHEN 'NO_INVOICE' THEN 3 WHEN 'OUTSTANDING' THEN 4 WHEN 'PAID' THEN 5 ELSE 6 END";
        // DataTable columns: selector, student, invoice, billed, outstanding, due date, state, action, spacer.
        $columns=[null,'s.full_name','p.invoice_number','p.final_amount','p.remaining_balance','p.due_date',$stateRank,null,null];
        $orderIndex=(int)($datatable['order'][0]['column']??0);
        $direction=strtolower($datatable['order'][0]['dir']??'asc')==='desc'?'DESC':'ASC';
        $order=isset($columns[$orderIndex])&&$columns[$orderIndex]?$columns[$orderIndex].' '.$direction:
            "$stateRank,p.due_date ASC,s.full_name ASC";
        $count=(int)$this->db->query("SELECT COUNT(*) total $from $where")->row_array()['total'];
        $length=max(10,min(100,(int)($datatable['length']??10)));
        $start=max(0,(int)($datatable['start']??0));
        $rows=$this->db->query("SELECT s.id,s.student_number,s.full_name,s.phone,p.invoice_number,p.final_amount,p.remaining_balance,p.due_date,p.status,$state payment_state,
            CASE WHEN p.due_date<CURDATE() AND COALESCE(p.remaining_balance,0)>0 THEN DATEDIFF(CURDATE(),p.due_date) ELSE 0 END overdue_days
            $from $where ORDER BY $order LIMIT $length OFFSET $start")->result_array();
        return ['total'=>$count,'filtered'=>$count,'rows'=>$rows];
    }

    private function lead_where(array $filters)
    {
        $conditions = ['l.row_status = 1'];
        if (!empty($filters['start_date'])) {
            $conditions[] = 'DATE(l.created_at) >= ' . $this->db->escape($filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $conditions[] = 'DATE(l.created_at) <= ' . $this->db->escape($filters['end_date']);
        }
        foreach (['source_code', 'university_id', 'assigned_to', 'status'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $conditions[] = 'l.' . $field . ' = ' . $this->db->escape($filters[$field]);
            }
        }
        if (!empty($filters['restrict_assigned_to'])) {
            $conditions[] = 'l.assigned_to = ' . (int) $filters['restrict_assigned_to'];
        }
        return implode(' AND ', $conditions);
    }

    private function payment_where(array $filters)
    {
        $conditions=['s.row_status=1'];
        if(!empty($filters['start_date'])) $conditions[]='DATE(s.created_at)>='.$this->db->escape($filters['start_date']);
        if(!empty($filters['end_date'])) $conditions[]='DATE(s.created_at)<='.$this->db->escape($filters['end_date']);
        foreach(['university_id','source_code'] as $field){if(isset($filters[$field])&&$filters[$field]!=='')$conditions[]=(($field==='university_id'?'s.':'l.').$field).'='.$this->db->escape($filters[$field]);}
        if(isset($filters['assigned_to'])&&$filters['assigned_to']!=='')$conditions[]='l.assigned_to='.(int)$filters['assigned_to'];
        if(!empty($filters['restrict_assigned_to']))$conditions[]='l.assigned_to='.(int)$filters['restrict_assigned_to'];
        return 'WHERE '.implode(' AND ',$conditions);
    }

    private function payment_scope_suffix(array $filters)
    {
        $conditions=[];
        if(!empty($filters['start_date']))$conditions[]='DATE(s.created_at)>='.$this->db->escape($filters['start_date']);
        if(!empty($filters['end_date']))$conditions[]='DATE(s.created_at)<='.$this->db->escape($filters['end_date']);
        if(isset($filters['university_id'])&&$filters['university_id']!=='')$conditions[]='s.university_id='.(int)$filters['university_id'];
        if(isset($filters['source_code'])&&$filters['source_code']!=='')$conditions[]='l.source_code='.$this->db->escape($filters['source_code']);
        if(isset($filters['assigned_to'])&&$filters['assigned_to']!=='')$conditions[]='l.assigned_to='.(int)$filters['assigned_to'];
        if(!empty($filters['restrict_assigned_to']))$conditions[]='l.assigned_to='.(int)$filters['restrict_assigned_to'];
        return $conditions?' AND '.implode(' AND ',$conditions):'';
    }

    private function trend_grouping($min_date, $max_date)
    {
        $days = ($min_date && $max_date)
            ? (int) floor((strtotime($max_date) - strtotime($min_date)) / 86400) + 1
            : 0;
        if ($days > 180) {
            return ['name' => 'monthly', 'select' => "DATE_FORMAT(l.created_at, '%Y-%m')", 'label' => "DATE_FORMAT(l.created_at, '%b %Y')"];
        }
        if ($days > 45) {
            return ['name' => 'weekly', 'select' => "DATE_FORMAT(l.created_at, '%x-%v')", 'label' => "CONCAT('W', DATE_FORMAT(l.created_at, '%v'), ' ', DATE_FORMAT(l.created_at, '%Y'))"];
        }
        return ['name' => 'daily', 'select' => 'DATE(l.created_at)', 'label' => "DATE_FORMAT(l.created_at, '%d %b')"];
    }

    private function format_trend(array $rows)
    {
        $periods = [];
        $sources = [];
        foreach ($rows as $row) {
            $key = $row['period_key'];
            $periods[$key] = $row['period_label'];
            $sources[$row['source_name']][$key] = (int) $row['total'];
        }
        $keys = array_keys($periods);
        $series = [];
        $totals = array_fill(0, count($keys), 0);
        foreach ($sources as $name => $values) {
            $data = [];
            foreach ($keys as $index => $key) {
                $value = (int) ($values[$key] ?? 0);
                $data[] = $value;
                $totals[$index] += $value;
            }
            $series[] = ['name' => $name, 'data' => $data];
        }
        return ['labels' => array_values($periods), 'total' => $totals, 'sources' => $series];
    }
}
