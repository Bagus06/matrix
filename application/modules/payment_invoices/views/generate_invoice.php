<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= @$utilitys['peyment_invoice']['data']['invoice_number']; ?></title>

    <style>
        @page {
            size: A4;
            margin-left: 40px;
            margin-right: 40px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .page {
            width: 210mm;
            height: 289mm;
            margin: auto;
            background: #fff;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        .content {
            padding-bottom: 120px;
            margin: 5px;
        }

        .header {
            display: flex;
            justify-content: space-between;
        }

        .left-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .left-header small {
            display: block;
            margin-top: 5px;
        }

        .right-header {
            text-align: right;
            font-size: 14px;
        }

        .right-header img {
            width: 180px;
        }

        .divider {
            border-top: 3px solid #1f3c88;
            margin: 15px 0;
        }

        .info {
            display: flex;
            font-size: 14px;
            margin-top: 30px;
        }

        .info div:first-child {
            width: 40%;
            padding-right: 20px;
        }

        .info div:last-child {
            width: 50%;
            padding-right: 20px;
        }

        .info p {
            margin: 4px 0;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            font-size: 14px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #333;
            overflow-wrap: break-word;
            word-wrap: break-word;
            white-space: normal;
        }

        th {
            background: #1f3c88;
            color: #fff;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .bank {
            margin-top: 20px;
            font-size: 14px;
        }

        .payment-wrapper {
            margin-top: 60px;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #222;
        }

        .payment-title {
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 8px;
        }

        .payment-card {
            flex: 1;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            margin-top: 5px;
        }

        .payment-full {
            width: 100%;
        }

        .payment-header {
            font-size: 15px;
            font-weight: 600;
            color: #111;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            border: 0px;
            margin: 0px !important;
        }

        .payment-table td {
            padding: 4px 0;
            vertical-align: top;
            border: 0px
        }

        .payment-label {
            width: 130px;
            color: #666;
            white-space: nowrap;
        }

        .payment-value {
            font-weight: 500;
            color: #000;
        }

        .payment-note {
            font-size: 12px;
            color: #777;
            line-height: 1.5;
        }

        .payment-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #f2f2f2;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .content,
        .terms {
            position: relative;
            z-index: 2;
        }

        .terms {
            position: absolute !important;
            bottom: 0px;
            font-size: 13px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            z-index: 0;
            pointer-events: none;

            opacity: 0.06;

            width: 550px;
            text-align: center;
        }

        .watermark img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: none;
            }

            .page {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="page">

        <div class="content">

            <div class="header">
                <div class="left-header">
                    <h1>INVOICE <?= @$utilitys['peyment_invoice']['data']['invoice_number'];  ?></h1>
                    <table style="border: none; margin:0px; padding:0px">
                        <tr>
                            <td style="border: none; padding:0px; padding-right:10px">
                                <strong>Office Address</strong><br>
                                EC Square Building, Second Floor<br>
                                Westhill, Chungam Calicut - 673005
                            </td>
                            <td style="border: none; border-left:solid 3px #1f3c88; padding:0px; padding-left:10px">
                                Phone : 8590822500<br>
                                WhatsApp : 9746239923<br>
                                Email : info@modway.co.in<br>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="right-header">
                    <img src="<?= base_url() . 'assets/img/logo/logo v1.0.png' ?>"><br>
                    <small>Website : www.modway.co.in</small>
                </div>
            </div>

            <div class="divider"></div>

            <p>
                <strong>InvoiceNo : </strong> <?= @$utilitys['peyment_invoice']['data']['invoice_number'];  ?><br>
                <strong>Invoice Date : </strong> <?= date('M d, Y') ?>
            </p>

            <div class="info">
                <div>
                    <p><strong>Student Name : </strong> <?= @$utilitys['student_data']['data']['full_name'];  ?></p>
                    <p><strong>Address : </strong> <?= @$utilitys['student_data']['data']['country'] . ', ' . @$utilitys['student_data']['data']['state'] . ', <br>' . @$utilitys['student_data']['data']['city'] . ', ' . @$utilitys['student_data']['data']['district']; ?></p>
                    <p><strong>Contact Number : </strong><?= @$utilitys['student_data']['data']['phone']; ?></p>
                    <p><strong>Email : </strong><?= @$utilitys['student_data']['data']['email']; ?></p>
                </div>

                <div>
                    <p><strong>StudentNo : </strong> <?= @$utilitys['student_data']['data']['student_number']; ?></p>
                    <p><strong>University : </strong> <?= @$utilitys['student_data']['data']['university_name'] . ' ( ' . @$utilitys['student_data']['data']['short_name'] . ' )'; ?></p>
                    <p><strong>Course : </strong> <?= @$utilitys['student_data']['data']['course_name'] . ' ( ' . @$utilitys['student_data']['data']['course_code'] . ' )'; ?></p>
                </div>
            </div>

            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="background:#1f3c88;color:#fff;width:80%">Description</th>
                        <th style="background:#1f3c88;color:#fff;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?= @$utilitys['peyment_invoice']['data']['information']; ?>
                        </td>
                        <td class="text-right"><?= INR(@$utilitys['peyment_invoice']['data']['amount']); ?></td>
                    </tr>
                    <?php if (hasDecimalValue(@$utilitys['student_data']['data']['additional_certificate_fee'])): ?>
                        <tr>
                            <td>Description additional certificate or attestation : <?= @$utilitys['student_data']['data']['additional_certificate']; ?></td>
                            <td class="text-right"><?= INR(@$utilitys['student_data']['data']['additional_certificate_fee']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (hasDecimalValue(@$utilitys['payment']['data']['total_discount_percent'])): ?>
                        <tr>
                            <td class="text-right"><strong>Discount :</strong></td>
                            <td class="text-right"><?= @$utilitys['payment']['data']['total_discount_percent']; ?>%</td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-right"><strong>Total Amount Due :</strong></td>
                        <td class="text-right"><strong><?= INR(@$utilitys['peyment_invoice']['data']['final_amount']); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="payment-wrapper">
                <div class="payment-title">
                    <strong>Payment Information</strong> <br>
                    <small>Please make your payment to MODWAY International Academy using the following methods.</small>
                </div>

                <!-- CASH -->
                <div class="payment-card">
                    <div class="payment-header">
                        CASH
                        <span class="payment-badge">Direct Payment</span>
                    </div>

                    <div class="payment-note">
                        Customer can make payment directly in cash at the office or to the authorized representative.
                    </div>
                </div>

                <!-- BANK TRANSFER -->
                <div class="payment-card">
                    <div class="payment-header">
                        BANK TRANSFER
                        <span class="payment-badge">NEFT / IMPS / RTGS</span>
                    </div>

                    <table class="payment-table">
                        <tr>
                            <td class="payment-label">Account Name</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['BANK1']['account_name']; ?></td>
                        </tr>

                        <tr>
                            <td class="payment-label">Account Number</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['BANK1']['account_identifier']; ?></td>
                        </tr>

                        <tr>
                            <td class="payment-label">Bank Name</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['BANK1']['bank_name']; ?></td>
                        </tr>

                        <tr>
                            <td class="payment-label">IFSC Code</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['BANK1']['ifsc_code']; ?></td>
                        </tr>

                        <tr>
                            <td class="payment-label">Branch</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['BANK1']['branch_name']; ?></td>
                        </tr>
                    </table>

                    <div class="payment-note">
                        Use Invoice Number as payment reference.
                    </div>
                </div>

                <!-- GOOGLE PAY -->
                <div class="payment-card">
                    <div class="payment-header">
                        GOOGLE PAY (GPay)
                        <span class="payment-badge">UPI Payment</span>
                    </div>

                    <table class="payment-table">
                        <tr>
                            <td class="payment-label">UPI Number</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['UPI1']['account_identifier']; ?></td>
                        </tr>

                        <tr>
                            <td class="payment-label">UPI ID</td>
                            <td class="payment-value"><?= @$utilitys['payment_methods']['UPI1']['account_name']; ?></td>
                        </tr>
                    </table>

                    <div class="payment-note">
                        Pay instantly using Google Pay with the above UPI details.
                    </div>
                </div>
            </div>
        </div>

        <!-- FIXED BOTTOM -->
        <div class="terms">
            <strong>Terms and Condition :</strong><br><br>
            Payment must be made within 1x24 hours from the invoice date for a minimum down payment of 0.00% or 0.00, and the maximum payment must be made before . The certificate will be issued after the payment receipt is issued.<br><br>
            For further questions, please reach out to MODWAY International Academy at info@modway.co.in or 8590822500
        </div>

        <div class="watermark">
            <img src="<?= base_url() . 'assets/img/logo/logoonly v1.0.png' ?>">
        </div>
    </div>

</body>

</html>