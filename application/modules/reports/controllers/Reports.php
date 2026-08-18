<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Reports extends CI_Controller
{
    protected $module = 'reports';
    protected $module_alias = 'RPT';

    public function __construct()
    {
        parent::__construct();
        # Uncomment for use user login check
        check_auth();
        $this->load->model('reports_model');
    }

    public function leads()
    {
        $this->load->view('index', ['report_filters' => $this->reports_model->lead_filters()]);
    }

    public function leads_overview()
    {
        $filters = $this->input->get();
        foreach (['start_date', 'end_date'] as $field) {
            if (!empty($filters[$field]) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters[$field])) {
                return $this->json_response(['status' => false, 'message' => 'Invalid date format.'], 422);
            }
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date']) && $filters['start_date'] > $filters['end_date']) {
            return $this->json_response(['status' => false, 'message' => 'Start date must not be after end date.'], 422);
        }
        if (user_group_check('GR_MKTST', get_user()['id']) && strtoupper(get_user()['username']) !== 'DEVELOPER') {
            $filters['restrict_assigned_to'] = get_user()['id'];
        }

        return $this->json_response(['status' => true, 'data' => $this->reports_model->leads_overview($filters)]);
    }

    private function json_response(array $payload, $status_code = 200)
    {
        $this->output->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

    public function conselor()
    {
        $can_view_all = $this->can_view_all_counselors();
        $filters = $this->reports_model->lead_filters();
        if (!$can_view_all) {
            $filters['counselors'] = [];
        }
        $this->load->view('index', ['report_filters' => $filters, 'can_view_all' => $can_view_all]);
    }

    public function counselor_overview()
    {
        $filters = $this->input->get();
        foreach (['start_date', 'end_date'] as $field) {
            if (!empty($filters[$field]) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters[$field])) {
                return $this->json_response(['status' => false, 'message' => 'Invalid date format.'], 422);
            }
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date']) && $filters['start_date'] > $filters['end_date']) {
            return $this->json_response(['status' => false, 'message' => 'Start date must not be after end date.'], 422);
        }
        if (!$this->can_view_all_counselors()) {
            unset($filters['assigned_to']);
            $filters['restrict_assigned_to'] = (int) get_user()['id'];
        }
        return $this->json_response(['status' => true, 'data' => $this->reports_model->counselor_overview($filters)]);
    }

    private function can_view_all_counselors()
    {
        $user = get_user();
        return strtoupper($user['username']) === 'DEVELOPER'
            || user_group_check('GR_ADMIN', $user['id']);
    }

    public function payment_receipt()
    {
        $can_view_all=$this->can_view_all_counselors();
        $filters=$this->reports_model->lead_filters();
        if(!$can_view_all)$filters['counselors']=[];
        $this->load->view('index',['report_filters'=>$filters,'can_view_all'=>$can_view_all]);
    }

    public function payment_receipt_overview()
    {
        $filters=$this->input->get();
        foreach(['start_date','end_date'] as $field){if(!empty($filters[$field])&&!preg_match('/^\d{4}-\d{2}-\d{2}$/',$filters[$field]))return $this->json_response(['status'=>false,'message'=>'Invalid date format.'],422);}
        if(!empty($filters['start_date'])&&!empty($filters['end_date'])&&$filters['start_date']>$filters['end_date'])return $this->json_response(['status'=>false,'message'=>'Start date must not be after end date.'],422);
        if(!$this->can_view_all_counselors()){unset($filters['assigned_to']);$filters['restrict_assigned_to']=(int)get_user()['id'];}
        return $this->json_response(['status'=>true,'data'=>$this->reports_model->payment_receipt_overview($filters)]);
    }

    public function payment_priority_table()
    {
        $input=$this->input->get();
        $filters=$input;
        if(!$this->can_view_all_counselors()){unset($filters['assigned_to']);$filters['restrict_assigned_to']=(int)get_user()['id'];}
        $result=$this->reports_model->payment_priority_datatable($filters,$input);
        $data=[];
        $no=(int)($input['start']??0);
        foreach($result['rows'] as $row){
            $no++;
            $states=['OVERDUE'=>['danger','Overdue'],'DUE_SOON'=>['warning','Due soon'],'NO_INVOICE'=>['secondary','No invoice'],'OUTSTANDING'=>['primary','Outstanding'],'PAID'=>['success','Paid']];
            $state=$states[$row['payment_state']]??['secondary',$row['payment_state']];
            $action='-';
            if($row['payment_state']==='OVERDUE')$action=(int)$row['overdue_days'].' days late';
            elseif($row['payment_state']==='NO_INVOICE')$action='Create invoice';
            elseif($row['payment_state']==='DUE_SOON')$action='Follow up';
            $student='<a class="font-weight-bold" href="'.base_url().'students/edit/'.encryptcst($row['id']).'">'.html_escape($row['full_name']).'</a><br><small>'.html_escape($row['student_number']).'</small>';
            $data[]=['',$student,html_escape($row['invoice_number']?:'-'),'₹ '.number_format((float)$row['final_amount'],2),'₹ '.number_format((float)$row['remaining_balance'],2),$row['due_date']?date('d M Y',strtotime($row['due_date'])):'-','<span class="badge badge-'.$state[0].'">'.$state[1].'</span>',$action,''];
        }
        return $this->json_response(['draw'=>(int)($input['draw']??0),'recordsTotal'=>$result['total'],'recordsFiltered'=>$result['filtered'],'data'=>$data]);
    }

    public function counseling_admissions_pdf()
    {
        $report_date = trim((string) ($this->input->get('report_date') ?: date('Y-m-d')));
        $tracking_month = trim((string) ($this->input->get('tracking_month') ?: date('Y-m', strtotime('first day of last month'))));

        if (!$this->valid_date($report_date, 'Y-m-d') || !$this->valid_date($tracking_month, 'Y-m')) {
            show_error('Invalid report date or monthly tracking period.', 422, 'Invalid report parameters');
            return;
        }

        $month_start = $tracking_month . '-01';
        $month_end = date('Y-m-t', strtotime($month_start));
        $restrict_assigned_to = $this->can_view_all_counselors() ? null : (int) get_user()['id'];
        $user = get_user();
        $filename = 'counseling-admissions-report-' . $report_date . '.pdf';
        $attachment = strtolower((string) $this->input->get('disposition')) === 'inline' ? 0 : 1;
        $logo_path = FCPATH . 'assets/img/logo/report-logo.png';
        if (!is_file($logo_path)) {
            $logo_path = FCPATH . 'assets/img/logo/logoonly v1.0.png';
        }

        $cache_directory = APPPATH . 'cache/reports/';
        $cache_id = hash('sha256', implode('|', [
            (int) $user['id'],
            $report_date,
            $tracking_month,
            $restrict_assigned_to === null ? 'ALL' : $restrict_assigned_to,
        ]));
        $cache_pdf = $cache_directory . 'counseling-' . $cache_id . '.pdf';
        $cache_meta = $cache_directory . 'counseling-' . $cache_id . '.version';
        $data_version = hash('sha256', implode('|', [
            $this->reports_model->counseling_admissions_cache_version(),
            (string) @filemtime(APPPATH . 'modules/reports/views/counseling_admissions_pdf.php'),
            (string) @filemtime($logo_path),
        ]));

        if (is_file($cache_pdf) && is_file($cache_meta)
            && hash_equals($data_version, trim((string) @file_get_contents($cache_meta)))) {
            $cached_pdf = @file_get_contents($cache_pdf);
            if ($cached_pdf !== false) {
                return $this->pdf_response($cached_pdf, $filename, $attachment);
            }
        }

        $report = $this->reports_model->counseling_admissions_report(
            $report_date,
            $month_start,
            $month_end,
            $restrict_assigned_to
        );

        $logo_data_uri = '';
        if (is_file($logo_path)) {
            $logo_data_uri = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
        }

        $view_data = array_merge($report, [
            'report_date' => $report_date,
            'tracking_month' => $tracking_month,
            'month_start' => $month_start,
            'month_end' => $month_end,
            'prepared_by' => trim((string) ($user['name'] ?? $user['username'] ?? '')),
            'scope_label' => $restrict_assigned_to ? 'Personal counselor report' : 'All counselors',
            'logo_data_uri' => $logo_data_uri,
        ]);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->load->view('counseling_admissions_pdf', $view_data, true), 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans', 'normal');
        $dompdf->getCanvas()->page_text(492, 824, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, 7, [0.36, 0.39, 0.43]);

        $pdf_content = $dompdf->output(['compress' => 1]);

        if ((is_dir($cache_directory) || @mkdir($cache_directory, 0775, true)) && is_writable($cache_directory)) {
            $temporary_pdf = $cache_pdf . '.' . uniqid('', true) . '.tmp';
            if (@file_put_contents($temporary_pdf, $pdf_content, LOCK_EX) !== false) {
                @rename($temporary_pdf, $cache_pdf);
                @file_put_contents($cache_meta, $data_version, LOCK_EX);
            }
            if (is_file($temporary_pdf)) {
                @unlink($temporary_pdf);
            }
        }

        return $this->pdf_response($pdf_content, $filename, $attachment);
    }

    private function pdf_response($pdf_content, $filename, $attachment)
    {
        $disposition = $attachment ? 'attachment' : 'inline';
        return $this->output
            ->set_content_type('application/pdf')
            ->set_header('Content-Disposition: ' . $disposition . '; filename="' . $filename . '"')
            ->set_header('Cache-Control: private, max-age=60')
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_output($pdf_content);
    }

    private function valid_date($value, $format)
    {
        $date = DateTime::createFromFormat('!' . $format, $value);
        return $date && $date->format($format) === $value;
    }
}
