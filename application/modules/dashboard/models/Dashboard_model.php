<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_model
{
    protected $module_name = 'dashboard';
    protected $error_prefix = 'DASH';

    public function __construct()
    {
        parent::__construct();
    }

    public function admin_overview()
    {
        $lead=$this->db->query("SELECT COUNT(*) total,SUM(UPPER(status)='YES') converted,SUM(UPPER(status)='PENDING') pending,SUM(UPPER(status)='NO') lost,SUM(UPPER(status)='PENDING' AND follow_up_date<CURDATE()) overdue FROM leads WHERE row_status=1")->row_array();
        $student=(int)$this->db->query("SELECT COUNT(*) total FROM students WHERE row_status=1")->row_array()['total'];
        $counselors=(int)$this->db->query("SELECT COUNT(*) total FROM users WHERE row_status=1 AND JSON_CONTAINS(permission_group,JSON_QUOTE('GR_MKTST')) AND NOT JSON_CONTAINS(permission_group,JSON_QUOTE('GR_ADMIN'))")->row_array()['total'];
        $payment=$this->db->query("SELECT COALESCE(SUM(final_amount),0) billed,COALESCE(SUM(final_amount-COALESCE(remaining_balance,0)),0) collected,COALESCE(SUM(remaining_balance),0) outstanding,SUM(COALESCE(remaining_balance,0)>0 AND due_date<CURDATE()) overdue FROM payments WHERE row_status=1")->row_array();
        $noInvoice=(int)$this->db->query("SELECT COUNT(*) total FROM students s LEFT JOIN payment_invoices i ON i.student_number=s.student_number AND i.row_status=1 WHERE s.row_status=1 AND i.id IS NULL")->row_array()['total'];
        $total=(int)($lead['total']??0);$converted=(int)($lead['converted']??0);
        $summary=['leads'=>$total,'converted'=>$converted,'pending'=>(int)($lead['pending']??0),'lost'=>(int)($lead['lost']??0),'followup_overdue'=>(int)($lead['overdue']??0),'conversion_rate'=>$total?round($converted/$total*100,1):0,'students'=>$student,'counselors'=>$counselors,'billed'=>(float)$payment['billed'],'collected'=>(float)$payment['collected'],'outstanding'=>(float)$payment['outstanding'],'payment_overdue'=>(int)$payment['overdue'],'no_invoice'=>$noInvoice];
        $monthlyRows=$this->db->query("SELECT months.period_key,DATE_FORMAT(CONCAT(months.period_key,'-01'),'%b %Y') label,COALESCE(l.leads,0) leads,COALESCE(l.converted,0) converted,COALESCE(s.students,0) students,COALESCE(p.billed,0) billed,COALESCE(p.collected,0) collected FROM (SELECT DATE_FORMAT(DATE_SUB(CURDATE(),INTERVAL n MONTH),'%Y-%m') period_key FROM (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11) x) months LEFT JOIN (SELECT DATE_FORMAT(created_at,'%Y-%m') period_key,COUNT(*) leads,SUM(UPPER(status)='YES') converted FROM leads WHERE row_status=1 GROUP BY period_key) l ON l.period_key=months.period_key LEFT JOIN (SELECT DATE_FORMAT(created_at,'%Y-%m') period_key,COUNT(*) students FROM students WHERE row_status=1 GROUP BY period_key) s ON s.period_key=months.period_key LEFT JOIN (SELECT DATE_FORMAT(created_at,'%Y-%m') period_key,SUM(final_amount) billed,SUM(final_amount-COALESCE(remaining_balance,0)) collected FROM payments WHERE row_status=1 GROUP BY period_key) p ON p.period_key=months.period_key ORDER BY months.period_key")->result_array();
        $monthly=['labels'=>[],'leads'=>[],'converted'=>[],'students'=>[],'billed'=>[],'collected'=>[]];foreach($monthlyRows as $r){$monthly['labels'][]=$r['label'];foreach(['leads','converted','students'] as $k)$monthly[$k][]=(int)$r[$k];foreach(['billed','collected'] as $k)$monthly[$k][]=(float)$r[$k];}
        $counselorRows=$this->db->query("SELECT COALESCE(NULLIF(TRIM(up.name),''),'Unassigned') name,COUNT(*) total,SUM(UPPER(l.status)='YES') converted,SUM(UPPER(l.status)='PENDING' AND l.follow_up_date<CURDATE()) overdue FROM leads l LEFT JOIN users u ON u.id=l.assigned_to AND u.row_status=1 LEFT JOIN user_profiles up ON up.user_id=u.id WHERE l.row_status=1 GROUP BY l.assigned_to,COALESCE(NULLIF(TRIM(up.name),''),'Unassigned') ORDER BY converted DESC,total DESC LIMIT 6")->result_array();foreach($counselorRows as &$r){foreach(['total','converted','overdue'] as $k)$r[$k]=(int)$r[$k];$r['rate']=$r['total']?round($r['converted']/$r['total']*100,1):0;}unset($r);
        $attention=$this->db->query("SELECT s.id,s.student_number,s.full_name,p.invoice_number,p.remaining_balance,p.due_date,DATEDIFF(CURDATE(),p.due_date) overdue_days FROM payments p INNER JOIN students s ON s.student_number=p.student_number AND s.row_status=1 WHERE p.row_status=1 AND COALESCE(p.remaining_balance,0)>0 AND p.due_date<CURDATE() ORDER BY p.due_date ASC LIMIT 8")->result_array();
        foreach($attention as &$row){$row['edit_key']=encryptcst($row['id']);unset($row['id']);$row['remaining_balance']=(float)$row['remaining_balance'];$row['overdue_days']=(int)$row['overdue_days'];}unset($row);
        return ['summary'=>$summary,'monthly'=>$monthly,'counselors'=>$counselorRows,'attention'=>$attention];
    }
}
