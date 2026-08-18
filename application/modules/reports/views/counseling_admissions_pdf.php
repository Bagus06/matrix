<?php defined('BASEPATH') or exit('No direct script access allowed');
$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$money = function ($value) {
    return number_format((float) $value, 2, '.', ',');
};
$contact_metric = function ($value) {
    return $value === null ? '-' : (int) $value;
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Counseling and Admissions Report</title>
    <style>
        @page { margin: 18px 18px 34px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #151a22; font-family: DejaVu Sans, sans-serif; font-size: 8.5px; }
        .brand-table, .meta-table, .section-title, .report-table, .signatures { width: 100%; border-collapse: collapse; }
        .brand-table td { border: 0; vertical-align: middle; }
        .brand-logo { width: 76px; text-align: center; }
        .brand-logo img { width: 66px; height: 72px; object-fit: contain; }
        .brand-copy { padding-left: 12px; }
        .brand-title { color: #2853a0; font-size: 21px; font-weight: 700; letter-spacing: .25px; }
        .brand-subtitle { color: #5d626b; font-size: 11px; font-style: italic; margin-top: 3px; }
        .brand-tagline { color: #db6b22; font-size: 8px; font-weight: 700; letter-spacing: 3px; margin-top: 7px; }
        .orange-rule { height: 5px; margin: 9px -18px 9px; background: #e36918; }
        .meta-table { margin-bottom: 7px; font-size: 8px; }
        .meta-table td { padding: 0 5px; }
        .meta-table td:first-child { padding-left: 0; }
        .meta-table td:last-child { padding-right: 0; text-align: right; color: #5d626b; }
        .meta-label { color: #242a33; font-weight: 700; }
        .meta-value { color: #2853a0; font-weight: 700; }
        .section { margin-top: 7px; page-break-inside: avoid; }
        .section-title td { height: 25px; color: #fff; font-weight: 700; text-transform: uppercase; }
        .section-name { width: 55%; padding: 5px 10px; background: #2853a0; font-size: 11px; letter-spacing: .2px; }
        .section-total-label { width: 28%; padding: 5px 8px; text-align: center; background: #ee6b0c; font-size: 9px; }
        .section-total-value { width: 17%; padding: 5px 8px; text-align: center; color: #d35f12 !important; background: #fae3d0; font-size: 10px; }
        .report-table { table-layout: fixed; }
        .report-table th, .report-table td { border: .65px solid #aebac9; padding: 4px 4px; line-height: 1.2; }
        .report-table thead th { color: #fff; background: #3f74b7; font-weight: 700; text-align: center; vertical-align: bottom; }
        .report-table tbody tr:nth-child(odd) td { background: #f8fafc; }
        .report-table tbody tr:nth-child(even) td { background: #e8eff8; }
        .report-table td { text-align: center; }
        .report-table td.name { text-align: left; white-space: nowrap; overflow: hidden; }
        .report-table tfoot td { color: #173d79; background: #dce7f5; font-weight: 700; text-align: center; border-top: 1.25px solid #df6a1c; }
        .report-table tfoot td.total-label { color: #fff; background: #2853a0; text-align: right; }
        .num { text-align: right !important; white-space: nowrap; }
        .empty { color: #737b86; font-style: italic; text-align: center !important; }
        .signatures { margin-top: 12px; font-size: 8px; }
        .signatures td { width: 50%; border: 0; padding: 0 3px; }
        .signature-line { display: inline-block; width: 150px; border-bottom: .7px solid #242a33; }
        .definitions { margin-top: 9px; padding: 6px 8px; color: #535b66; background: #f4f6f8; border-left: 3px solid #2853a0; font-size: 6.7px; line-height: 1.45; }
        .definitions b { color: #2c333d; }
        .confidential { position: fixed; left: 0; right: 0; bottom: -20px; color: #777f89; font-size: 6.5px; font-style: italic; text-align: center; }
    </style>
</head>
<body>
    <table class="brand-table">
        <tr>
            <td class="brand-logo">
                <?php if (!empty($logo_data_uri)): ?><img src="<?= $escape($logo_data_uri); ?>" alt="MODWAY International Academy"><?php endif; ?>
            </td>
            <td class="brand-copy">
                <div class="brand-title">MODWAY INTERNATIONAL ACADEMY</div>
                <div class="brand-subtitle">Daily Counselling &amp; Admissions Report</div>
                <div class="brand-tagline">LEARN&nbsp;&nbsp;&middot;&nbsp;&nbsp;GROW&nbsp;&nbsp;&middot;&nbsp;&nbsp;ACHIEVE</div>
            </td>
        </tr>
    </table>
    <div class="orange-rule"></div>

    <table class="meta-table">
        <tr>
            <td><span class="meta-label">Date:</span> <span class="meta-value"><?= date('d-M-Y', strtotime($report_date)); ?></span></td>
            <td><?= $escape($scope_label); ?></td>
            <td><span class="meta-label">Monthly Tracking Period:</span> <?= date('F Y', strtotime($month_start)); ?></td>
        </tr>
    </table>

    <div class="section">
        <table class="section-title">
            <tr>
                <td class="section-name">Section 1&nbsp;&nbsp;&middot;&nbsp;&nbsp;Daily Lead Tracking</td>
                <td class="section-total-label">Total Called (Today)</td>
                <td class="section-total-value"><?= (int) $daily['totals']['leads_called']; ?></td>
            </tr>
        </table>
        <table class="report-table">
            <colgroup><col style="width:6%"><col style="width:28%"><col style="width:17%"><col style="width:17%"><col style="width:17%"><col style="width:15%"></colgroup>
            <thead><tr><th>#</th><th>Counselor</th><th>Leads<br>Called</th><th>Responded</th><th>No Response</th><th>Admissions</th></tr></thead>
            <tbody>
                <?php if (empty($daily['rows'])): ?>
                    <tr><td colspan="6" class="empty">No activity recorded for this date.</td></tr>
                <?php else: foreach ($daily['rows'] as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1; ?></td><td class="name"><?= $escape($row['counselor']); ?></td>
                        <td><?= $contact_metric($row['leads_called']); ?></td><td><?= $contact_metric($row['responded']); ?></td><td><?= $contact_metric($row['no_response']); ?></td><td><?= (int) $row['admissions']; ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
            <tfoot><tr><td></td><td class="total-label">TOTAL</td><td><?= $contact_metric($daily['totals']['leads_called']); ?></td><td><?= $contact_metric($daily['totals']['responded']); ?></td><td><?= $contact_metric($daily['totals']['no_response']); ?></td><td><?= (int) $daily['totals']['admissions']; ?></td></tr></tfoot>
        </table>
    </div>

    <div class="section">
        <table class="section-title">
            <tr>
                <td class="section-name">Section 2&nbsp;&nbsp;&middot;&nbsp;&nbsp;Monthly Tracking</td>
                <td class="section-total-label">Total Called (Month)</td>
                <td class="section-total-value"><?= (int) $monthly['totals']['leads_called']; ?></td>
            </tr>
        </table>
        <table class="report-table">
            <colgroup><col style="width:6%"><col style="width:28%"><col style="width:17%"><col style="width:17%"><col style="width:17%"><col style="width:15%"></colgroup>
            <thead><tr><th>#</th><th>Counselor</th><th>Leads<br>Called</th><th>Responded</th><th>No Response</th><th>Admissions</th></tr></thead>
            <tbody>
                <?php if (empty($monthly['rows'])): ?>
                    <tr><td colspan="6" class="empty">No activity recorded for this month.</td></tr>
                <?php else: foreach ($monthly['rows'] as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1; ?></td><td class="name"><?= $escape($row['counselor']); ?></td>
                        <td><?= $contact_metric($row['leads_called']); ?></td><td><?= $contact_metric($row['responded']); ?></td><td><?= $contact_metric($row['no_response']); ?></td><td><?= (int) $row['admissions']; ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
            <tfoot><tr><td></td><td class="total-label">TOTAL</td><td><?= $contact_metric($monthly['totals']['leads_called']); ?></td><td><?= $contact_metric($monthly['totals']['responded']); ?></td><td><?= $contact_metric($monthly['totals']['no_response']); ?></td><td><?= (int) $monthly['totals']['admissions']; ?></td></tr></tfoot>
        </table>
    </div>

    <div class="section">
        <table class="section-title"><tr><td class="section-name" colspan="3">Section 3&nbsp;&nbsp;&middot;&nbsp;&nbsp;Admission Report</td></tr></table>
        <table class="report-table">
            <colgroup><col style="width:6%"><col style="width:27%"><col style="width:15%"><col style="width:18%"><col style="width:18%"><col style="width:16%"></colgroup>
            <thead><tr><th>#</th><th>Counselor</th><th>Total<br>Admissions</th><th>Total Fees</th><th>Advance<br>Received</th><th>Balance<br>Receivable</th></tr></thead>
            <tbody>
                <?php if (empty($admission['rows'])): ?>
                    <tr><td colspan="6" class="empty">No admissions recorded for this month.</td></tr>
                <?php else: foreach ($admission['rows'] as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1; ?></td><td class="name"><?= $escape($row['counselor']); ?></td><td><?= (int) $row['total_admissions']; ?></td>
                        <td class="num">&#8377; <?= $money($row['total_fees']); ?></td><td class="num">&#8377; <?= $money($row['advance_received']); ?></td><td class="num">&#8377; <?= $money($row['balance_receivable']); ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
            <tfoot><tr><td></td><td class="total-label">TOTAL</td><td><?= (int) $admission['totals']['total_admissions']; ?></td><td class="num">&#8377; <?= $money($admission['totals']['total_fees']); ?></td><td class="num">&#8377; <?= $money($admission['totals']['advance_received']); ?></td><td class="num">&#8377; <?= $money($admission['totals']['balance_receivable']); ?></td></tr></tfoot>
        </table>
    </div>

    <table class="signatures"><tr><td>Prepared by: <span class="signature-line"><?= $escape($prepared_by); ?></span></td><td>Reviewed by: <span class="signature-line">&nbsp;</span></td></tr></table>

    <div class="definitions">
        <b>Data basis:</b> Called counts each distinct lead once per counselor in the selected period, even when multiple call logs exist. Responded and No Response use the latest call result for that lead within the period. Admissions use active student records linked by enquiry number. Financial totals use the latest active payment amount and actual active receipts recorded up to the report date.
    </div>
    <div class="confidential">MODWAY International Academy &nbsp;&middot;&nbsp; Confidential - for internal use only</div>
</body>
</html>
