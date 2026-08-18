<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt Voucher - <?= @$utilitys['payment_receipt']['data']['receipt_number']; ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #efefef;
            font-family: Arial, Helvetica, sans-serif;
        }

        html,
        body,
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .page {
            width: 297mm;
            height: 118mm;
            margin: 20px auto;
            background: #fff;
            padding: 0 13mm 13mm 0;
            position: relative;
        }

        .receipt {
            width: 100%;
            height: 100%;
            border: 4px solid #173a8f;
            border-radius: 18px;
            padding: 5px 15px;
            position: relative;
        }

        /* ================= Header ================= */

        .header {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
        }

        .title {
            width: 340px;
            background: #173a8f;
            color: #fff;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            padding: 8px 0;
            margin-top: 10px;
            letter-spacing: .5px;
        }

        .logo {
            position: absolute;
            right: 0;
            top: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo img {
            height: 70px;
        }

        .logo-text {
            line-height: 1.2;
        }

        .logo-text h2 {
            color: #f58220;
            font-size: 22px;
        }

        .logo-text p {
            color: #173a8f;
            font-size: 15px;
            font-weight: bold;
        }

        /* ================= Content ================= */

        .content-top {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .content-bottom {
            display: flex;
            justify-content: space-between;
        }

        .left {
            width: 58%;
        }

        .right {
            width: 30%;
        }

        .full {
            width: 100%;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .address {
            line-height: 1.45;
            font-size: 15px;
            margin-bottom: 16px;
        }

        .field {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-size: 15px;
        }

        .field label {
            width: 130px;
            font-weight: bold;
        }

        .field span.colon {
            width: 15px;
            text-align: center;
            font-weight: bold;
        }

        .field .line {
            flex: 1;
            border-bottom: 1px solid #555;
            min-height: 22px;
            display: flex;
            align-items: center;
            padding-left: 8px;
        }

        /* ================= Right ================= */

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .summary-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .summary-table td:first-child {
            font-weight: bold;
            text-align: right;
            width: 58%;
            padding-right: 8px;
        }

        .summary-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        .summary-table td:last-child {
            text-align: right;
        }

        /* ================= Signature ================= */

        .signature {
            display: flex;
            justify-content: space-between;
            position: absolute;
            right: 45px;
            padding-top: 10px;
            width: 220px;
            text-align: right;
            font-size: 15px;
            line-height: 1.7;
        }

        @media print {

            body {
                background: white;
            }

            .page {
                margin: 0;
                width: 297mm;
                height: 118mm;
            }
        }
    </style>

</head>

<body onload="window.print()">

    <div class="page">

        <div class="receipt">

            <div class="header">

                <div class="title">
                    RECEIPT VOUCHER
                </div>

                <div class="logo">
                    <img src="<?= base_url() . 'assets/img/logo/logo v1.0.png' ?>">
                </div>

            </div>

            <div class="content-top">

                <div class="left">

                    <div class="section-title">
                        Received From
                    </div>

                    <div class="address">
                        <?= @$utilitys['student']['data']['full_name']; ?><br>
                        <?= @$utilitys['student']['data']['district']; ?>, <?= @$utilitys['student']['data']['city']; ?><br>
                        <?= @$utilitys['student']['data']['phone']; ?><br>
                        <?= @$utilitys['student']['data']['email']; ?>
                    </div>

                </div>

                <div class="right">

                    <table class="summary-table">

                        <tr>
                            <td>Referance Invoice</td>
                            <td>:</td>
                            <td><?= @$utilitys['payment']['data']['invoice_number']; ?></td>
                        </tr>

                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td><?= date('d F Y', strtotime(@$utilitys['payment_receipt']['data']['receipt_date'])); ?></td>
                        </tr>

                        <tr>
                            <td>Payment Method</td>
                            <td>:</td>
                            <td><?= @$utilitys['payment_receipt']['data']['method_name'] ?></td>
                        </tr>

                        <tr>
                            <td>Amount</td>
                            <td>:</td>
                            <td><?= INR(@$utilitys['payment_receipt']['data']['amount']) ?></td>
                        </tr>

                    </table>

                </div>

            </div>

            <div class="content-bottom">

                <div class="full">
                    <div class="field">
                        <label>Student No</label>
                        <span class="colon">:</span>
                        <div class="line"><?= @$utilitys['student']['data']['student_number']; ?></div>
                    </div>

                    <div class="field">
                        <label>For</label>
                        <span class="colon">:</span>
                        <div class="line">
                            <?= @$utilitys['payment_receipt']['data']['information'] ?>
                        </div>
                    </div>

                    <div class="field">
                        <label>Note</label>
                        <span class="colon">:</span>
                        <div class="line"><?= @$utilitys['payment_receipt']['data']['note'] ?></div>
                    </div>

                    <div class="field">
                        <label>Received By</label>
                        <span class="colon">:</span>
                        <div class="line">
                            <?= @$utilitys['payment_method']['data']['account_name'] .  ((@$utilitys['payment_method']['data']['category'] == 'CASH') ? ' ( ' . @$utilitys['payment_receipt']['data']['created_by_name'] . ' )' : ''); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="right">

                <div class="signature">
                    Signature : <?= @$utilitys['payment_receipt']['data']['created_by_name']; ?><br>
                    Date : <?= date('l, d F Y'); ?>
                </div>

            </div>

        </div>

    </div>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>