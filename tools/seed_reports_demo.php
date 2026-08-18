<?php

/**
 * Seed a deterministic 12-month demo dataset for Reports.
 *
 * Usage:
 *   php tools/seed_reports_demo.php --apply
 *   php tools/seed_reports_demo.php --cleanup
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

$mode = $argv[1] ?? '';
if (!in_array($mode, ['--apply', '--cleanup'], true)) {
    exit("Use --apply to seed or --cleanup to remove report demo data.\n");
}

$env = [];
foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if ($line[0] === '#' || strpos($line, '=') === false) continue;
    [$key, $value] = explode('=', $line, 2);
    $env[trim($key)] = trim(trim($value), "\"'");
}

$db = new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);
if ($db->connect_errno) exit("Database connection failed: {$db->connect_error}\n");
$db->set_charset('utf8mb4');
$db->begin_transaction();

try {
    // Remove both the previous D26 seed and standards-compliant demo rows through their marker.
    $marker = "l.source_information LIKE 'REPORT_DEMO_12M|%'";
    $db->query("DELETE b FROM apps_booked_number b INNER JOIN payment_receipts r ON r.receipt_number=b.number INNER JOIN students s ON s.student_number=r.student_number INNER JOIN leads l ON l.enquiry_number=s.enquiry_number WHERE $marker");
    $db->query("DELETE b FROM apps_booked_number b INNER JOIN leads l ON l.enquiry_number = b.number WHERE $marker");
    $db->query("DELETE b FROM apps_booked_number b INNER JOIN students s ON s.student_number = b.number INNER JOIN leads l ON l.enquiry_number = s.enquiry_number WHERE $marker");
    $db->query("DELETE b FROM apps_booked_number b INNER JOIN payment_invoices i ON i.invoice_number = b.number INNER JOIN students s ON s.student_number = i.student_number INNER JOIN leads l ON l.enquiry_number = s.enquiry_number WHERE $marker");
    $db->query("DELETE r FROM payment_receipts r INNER JOIN students s ON s.student_number=r.student_number INNER JOIN leads l ON l.enquiry_number=s.enquiry_number WHERE $marker");
    $db->query("DELETE p FROM payments p INNER JOIN students s ON s.student_number = p.student_number INNER JOIN leads l ON l.enquiry_number = s.enquiry_number WHERE $marker");
    $db->query("DELETE i FROM payment_invoices i INNER JOIN students s ON s.student_number = i.student_number INNER JOIN leads l ON l.enquiry_number = s.enquiry_number WHERE $marker");
    $db->query("DELETE s FROM students s INNER JOIN leads l ON l.enquiry_number = s.enquiry_number WHERE $marker");
    $db->query("DELETE l FROM leads l WHERE $marker");
    $db->query("DELETE FROM apps_booked_number WHERE number LIKE 'D26E%' OR number LIKE 'D26S%'");
    $db->query("DELETE p FROM payments p INNER JOIN students s ON s.student_number = p.student_number WHERE s.student_number LIKE 'D26S%'");
    $db->query("DELETE FROM payment_invoices WHERE student_number LIKE 'D26S%'");
    $db->query("DELETE FROM students WHERE student_number LIKE 'D26S%'");
    $db->query("DELETE FROM leads WHERE enquiry_number LIKE 'D26E%' AND source_information LIKE 'REPORT_DEMO_12M|%'");

    if ($mode === '--cleanup') {
        $db->commit();
        exit("Report demo data removed.\n");
    }

    $universities = fetchAll($db, "SELECT id FROM universities WHERE row_status = 1 ORDER BY id");
    $courses = fetchAll($db, "SELECT id, university_id, final_fee FROM university_courses WHERE row_status = 1 ORDER BY id");
    $users = fetchAll($db, "SELECT u.id, p.name
        FROM users u
        INNER JOIN user_profiles p ON p.user_id = u.id
        WHERE u.row_status = 1
          AND UPPER(u.username) <> 'DEVELOPER'
          AND JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_MKTST'))
          AND NOT JSON_CONTAINS(u.permission_group, JSON_QUOTE('GR_ADMIN'))
          AND NULLIF(TRIM(p.name), '') IS NOT NULL
        ORDER BY u.id");
    $sources = fetchAll($db, "SELECT source_code, source_name FROM leads_sources WHERE row_status = 1 ORDER BY id");
    $paymentMethods = fetchAll($db, "SELECT id, method_name FROM payment_methods WHERE row_status = 1 AND UPPER(status) = 'ACTIVE' ORDER BY id");
    $creator = (int) (($db->query("SELECT id FROM users WHERE UPPER(username) = 'DEVELOPER' AND row_status = 1 LIMIT 1")->fetch_assoc()['id'] ?? 0));

    if (!$universities || !$courses || !$users || !$sources || !$paymentMethods || !$creator) {
        throw new RuntimeException('Required master data (university/course/user/source/payment method/developer) is incomplete.');
    }

    $coursesByUniversity = [];
    foreach ($courses as $course) $coursesByUniversity[$course['university_id']][] = $course;

    $firstNames = ['Aarav','Aditi','Akash','Ananya','Arjun','Diya','Farhan','Fatima','Ishaan','Kavya','Meera','Neha','Nikhil','Priya','Rahul','Riya','Rohan','Saanvi','Vikram','Zoya'];
    $lastNames = ['Sharma','Patel','Kumar','Singh','Das','Gupta','Verma','Khan','Reddy','Nair','Mehta','Joshi'];
    $states = ['Assam','Delhi','Gujarat','Karnataka','Kerala','Maharashtra','Rajasthan','Tamil Nadu','Uttar Pradesh','West Bengal'];
    $sourceWeights = [20, 22, 18, 8, 17, 15];
    $statusValues = array_merge(array_fill(0, 38, 'YES'), array_fill(0, 47, 'PENDING'), array_fill(0, 15, 'NO'));
    $paymentValues = array_merge(array_fill(0, 48, 'PAID'), array_fill(0, 32, 'PARTIAL'), array_fill(0, 20, 'UNPAID'));

    $leadSql = "INSERT INTO leads
        (university_id,course_id,enquiry_number,source_code,source_information,full_name,first_name,last_name,date_of_birth,aadhaar_number,father_name,mother_name,religion,gender,email,phone,whatsapp_number,country,state,city,district,address,postal_code,priority,assigned_to,assigned_to_name,follow_up_date,status,note,row_status,created_by,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $studentSql = "INSERT INTO students
        (student_number,university_id,course_id,dept,certificate,course_status,completed_date,session,enquiry_number,final_fees,additional_certificate,additional_certificate_fee,full_name,first_name,last_name,date_of_birth,aadhaar_number,father_name,mother_name,religion,gender,email,phone,whatsapp_number,country,state,city,district,address,postal_code,row_status,created_by,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $paymentSql = "INSERT INTO payments
        (student_number,total_amount,additional_certificate_fee,discount,aditional_discount,total_discount,tax_percent,final_amount,advance_percent,advance_amount,advance_date,invoice_number,remaining_balance,due_date,final_payment,final_payment_date,status,row_status,created_by,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $invoiceSql = "INSERT INTO payment_invoices
        (student_number,invoice_number,invoice_date,information,amount,tax_percent,final_amount,request_by,request_by_name,request_date,approval_status,approval_by,approval_by_name,approval_date,sending_status,row_status,created_by,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $receiptSql = "INSERT INTO payment_receipts
        (payment_method_id,method_name,student_number,invoice_number,receipt_number,receipt_date,receipt_for,information,note,outstanding_balance,amount,remaining_balance,sending_status,row_status,created_by,created_by_name,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $contactLogSql = "INSERT INTO lead_contact_logs
        (lead_id,counselor_user_id,contact_channel,contact_context,contact_result,note,row_status,created_by,updated_by,created_at,updated_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)";

    $leadStmt = $db->prepare($leadSql);
    $studentStmt = $db->prepare($studentSql);
    $paymentStmt = $db->prepare($paymentSql);
    $invoiceStmt = $db->prepare($invoiceSql);
    $receiptStmt = $db->prepare($receiptSql);
    $contactLogStmt = $db->prepare($contactLogSql);
    mt_srand(260809);
    $today = new DateTimeImmutable('today');
    $start = $today->modify('-364 days');
    $studentCount = 0;
    $contactLogCount = 0;
    $numberCounters = [];
    $completedByUniversity = [];
    $createdDates = [];
    for ($i = 1; $i <= 600; $i++) {
        $createdDates[] = $start->modify('+' . mt_rand(0, 364) . ' days')
            ->setTime(mt_rand(8, 20), mt_rand(0, 59), mt_rand(0, 59));
    }
    usort($createdDates, function (DateTimeImmutable $a, DateTimeImmutable $b) {
        return $a->getTimestamp() <=> $b->getTimestamp();
    });

    for ($i = 1; $i <= 600; $i++) {
        $created = $createdDates[$i - 1];
        $university = pick($universities);
        $course = pick($coursesByUniversity[$university['id']]);
        $user = pick($users);
        $source = weightedPick($sources, $sourceWeights);
        $first = pick($firstNames); $last = pick($lastNames); $full = "$first $last";
        $gender = mt_rand(0, 1) ? 'MALE' : 'FEMALE';
        $status = pick($statusValues);
        $enquiry = nextNumber($db, 'ENQ-' . $created->format('ymd') . '-', 3, $numberCounters);
        $birth = $created->modify('-' . mt_rand(18, 29) . ' years')->format('Y-m-d');
        $aadhaar = str_pad((string) (700000000000 + $i), 12, '0', STR_PAD_LEFT);
        $phone = '91' . str_pad((string) (7000000000 + $i), 10, '0', STR_PAD_LEFT);
        $state = pick($states);
        $priority = pick(['LOW','MEDIUM','MEDIUM','HIGH','HIGH']);
        $followUp = $created->modify('+' . mt_rand(1, 14) . ' days')->format('Y-m-d');
        if ($status === 'PENDING' && mt_rand(1, 100) <= 35) $followUp = $today->modify('+' . mt_rand(1, 21) . ' days')->format('Y-m-d');
        $createdAt = $created->format('Y-m-d H:i:s');
        $email = strtolower($first . '.' . $last . '.' . $i . '@demo.matrix.test');
        $sourceInfo = 'REPORT_DEMO_12M|' . $source['source_name'];
        $note = 'Generated demo lead for 12-month report preview';
        $city = 'Demo City'; $district = 'Demo District'; $address = 'Demo address ' . $i; $postal = (string) (100000 + ($i % 899999));
        $father = 'Mr ' . $last; $mother = 'Mrs ' . $last; $religion = pick(['HINDU','MUSLIM','CHRISTIAN','OTHER']);
        execute($leadStmt, [$university['id'],$course['id'],$enquiry,$source['source_code'],$sourceInfo,$full,$first,$last,$birth,$aadhaar,$father,$mother,$religion,$gender,$email,$phone,$phone,'INDIA',$state,$city,$district,$address,$postal,$priority,$user['id'],$user['name'],$followUp,$status,$note,1,$creator,$creator,$createdAt,$createdAt]);
        $leadId = (int) $db->insert_id;
        bookNumber($db, $enquiry);

        // Contact outcome is intentionally separate from conversion status.
        // Repeated records exercise the report rule: one distinct lead per period.
        if (mt_rand(1, 100) <= 88) {
            $callCount = mt_rand(1, 4);
            for ($callIndex = 0; $callIndex < $callCount; $callIndex++) {
                $callAt = $created->modify('+' . ($callIndex * mt_rand(1, 5)) . ' days')->setTime(mt_rand(9, 18), mt_rand(0, 59), mt_rand(0, 59));
                if ($callAt > $today->setTime(23, 59, 59)) break;
                $isLastCall = $callIndex === $callCount - 1;
                $contactResult = ($isLastCall && in_array($status, ['YES', 'NO'], true))
                    ? 'RESPONDED'
                    : pick(['RESPONDED', 'NO_RESPONSE', 'NO_RESPONSE']);
                $contactContext = $callIndex === 0 ? 'NEW_LEAD' : 'FOLLOW_UP';
                $callTimestamp = $callAt->format('Y-m-d H:i:s');
                execute($contactLogStmt, [$leadId,$user['id'],'CALL',$contactContext,$contactResult,'Generated demo call log',1,$user['id'],$user['id'],$callTimestamp,$callTimestamp]);
                $contactLogCount++;
            }
        }

        if ($status !== 'YES') continue;
        $studentCount++;
        $studentNumber = nextNumber($db, $created->format('my'), 4, $numberCounters);
        $invoiceNumber = nextNumber($db, '#INV-' . $created->format('Y'), 4, $numberCounters);
        $fee = (float) $course['final_fee'];
        $session = $created->format('Y') . ' - ' . $created->modify('+1 year')->format('Y');
        $discount = round($fee * pick([0, 0.05, 0.10]), 2);
        $finalAmount = $fee - $discount;
        $paymentStatus = pick($paymentValues);
        $advancePercent = $paymentStatus === 'PAID' ? 100 : ($paymentStatus === 'PARTIAL' ? pick([25,30,40,50]) : 0);
        $advance = round($finalAmount * $advancePercent / 100, 2);
        $remaining = $finalAmount - $advance;
        $advanceDate = $advance > 0 ? $created->modify('+' . mt_rand(1, 10) . ' days')->format('Y-m-d') : null;
        $dueDate = $created->modify('+' . mt_rand(30, 90) . ' days')->format('Y-m-d');
        $finalPayment = $paymentStatus === 'PAID' ? $remaining : null;
        $finalDate = $paymentStatus === 'PAID' ? $created->modify('+' . mt_rand(10, 30) . ' days')->format('Y-m-d') : null;
        $remaining = $paymentStatus === 'PAID' ? 0 : $remaining;

        // Match the Student edit workflow: only fully paid students can be marked complete.
        // Use three students per university from a prior academic year, so every University
        // Report has realistic, currently exportable completion data.
        $completedCount = $completedByUniversity[$university['id']] ?? 0;
        $courseStatus = 'ACTIVE';
        $completedDate = null;
        if ($paymentStatus === 'PAID'
            && (int) $created->format('Y') < (int) $today->format('Y')
            && $completedCount < 3) {
            $courseStatus = 'COMPLETED';
            $completedDate = $today->format('Y-m-d');
            $completedByUniversity[$university['id']] = $completedCount + 1;
        }

        execute($studentStmt, [$studentNumber,$university['id'],$course['id'],'General',null,$courseStatus,$completedDate,$session,$enquiry,$fee,null,0,$full,$first,$last,$birth,$aadhaar,$father,$mother,$religion,$gender,$email,$phone,$phone,'INDIA',$state,$city,$district,$address,$postal,1,$creator,$creator,$createdAt,$createdAt]);
        bookNumber($db, $studentNumber);

        execute($paymentStmt, [$studentNumber,$fee,0,$discount,0,$discount,0,$finalAmount,$advancePercent,$advance,$advanceDate,$invoiceNumber,$remaining,$dueDate,$finalPayment,$finalDate,$paymentStatus,1,$creator,$creator,$createdAt,$createdAt]);
        $invoiceDate = $created->modify('+' . mt_rand(0, 3) . ' days')->format('Y-m-d');
        execute($invoiceStmt, [$studentNumber,$invoiceNumber,$invoiceDate,'Course fee invoice',$fee,0,$finalAmount,$creator,'DEVELOPER',$invoiceDate,'APPROVED',$creator,'DEVELOPER',$invoiceDate,1,1,$creator,$creator,$createdAt,$createdAt]);
        bookNumber($db, $invoiceNumber);

        if ($advance > 0) {
            $receiptMethod = pick($paymentMethods);
            $receiptDate = new DateTimeImmutable($advanceDate ?: $invoiceDate);
            $receiptNumber = nextNumber($db, '#RCT-' . $receiptDate->format('Y'), 4, $numberCounters);
            $receiptFor = $paymentStatus === 'PAID' ? 'final_payment' : 'down_payment';
            $receiptInformation = $paymentStatus === 'PAID' ? 'Final Payment' : 'Down Payment';
            $receiptCreatedAt = $receiptDate->setTime(12, 0, 0)->format('Y-m-d H:i:s');
            execute($receiptStmt, [$receiptMethod['id'],$receiptMethod['method_name'],$studentNumber,$invoiceNumber,$receiptNumber,$receiptDate->format('Y-m-d'),$receiptFor,$receiptInformation,'Generated demo payment receipt',$finalAmount,$advance,$remaining,null,1,$creator,'DEVELOPER',$creator,$receiptCreatedAt,$receiptCreatedAt]);
            bookNumber($db, $receiptNumber);
        }
    }

    $db->commit();
    echo "Seed complete: 600 leads, {$contactLogCount} call logs, {$studentCount} students, {$studentCount} payments/invoices, and payment receipts for every paid amount.\n";
} catch (Throwable $error) {
    $db->rollback();
    fwrite(STDERR, "Seed failed and was rolled back: {$error->getMessage()}\n");
    exit(1);
}

function fetchAll(mysqli $db, string $sql): array { return $db->query($sql)->fetch_all(MYSQLI_ASSOC); }
function pick(array $items) { return $items[array_rand($items)]; }
function weightedPick(array $items, array $weights) {
    $roll = mt_rand(1, array_sum($weights));
    foreach ($items as $index => $item) { $roll -= $weights[$index] ?? 1; if ($roll <= 0) return $item; }
    return end($items);
}
function execute(mysqli_stmt $statement, array $values): void {
    $types = '';
    foreach ($values as $value) $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
    $statement->bind_param($types, ...$values);
    $statement->execute();
}
function nextNumber(mysqli $db, string $prefix, int $digits, array &$counters): string {
    $key = $prefix . '|' . $digits;
    if (!array_key_exists($key, $counters)) {
        $safePrefix = $db->real_escape_string($prefix);
        $result = $db->query("SELECT COALESCE(MAX(CAST(RIGHT(number, $digits) AS UNSIGNED)), 0) last_sequence FROM apps_booked_number WHERE number LIKE '{$safePrefix}%'");
        $counters[$key] = (int) $result->fetch_assoc()['last_sequence'];
    }
    $counters[$key]++;
    return $prefix . str_pad((string) $counters[$key], $digits, '0', STR_PAD_LEFT);
}
function bookNumber(mysqli $db, string $number): void {
    $statement = $db->prepare('INSERT INTO apps_booked_number (number, used) VALUES (?, 1)');
    $statement->bind_param('s', $number);
    $statement->execute();
}
