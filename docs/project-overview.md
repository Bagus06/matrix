# Matrix Project Overview

Dokumen ini menyimpan hasil pemetaan proyek Matrix per 9 Agustus 2026. Gunakan sebagai konteks awal sebelum mengubah modul atau database.

## Stack dan arsitektur

- PHP, CodeIgniter 3.1.13, dan Modular Extensions/HMVC.
- MySQL melalui driver `mysqli`; konfigurasi koneksi berasal dari `.env`.
- AdminLTE 3.2, Bootstrap, jQuery, DataTables, Select2, SweetAlert2, Toastr, dan ECharts.
- PhpSpreadsheet untuk Excel, TCPDF/Dompdf untuk PDF, dan PHPWord untuk Word.
- Entry point: `index.php`.
- Default route: `dashboard/main`; autentikasi: `auth/login` dan `auth/logout`.
- Modul berada di `application/modules/{module}` dan umumnya mempunyai controller, model, view, `cssload.php`, dan `jsload.php`.

Layout `application/views/admin/index.php` memuat view modul berdasarkan URI. `MY_Router` dan `MY_Loader` mengaktifkan routing HMVC.

## Domain utama

Alur bisnis utama:

```text
Lead/Enquiry
  -> University + Course
  -> Student
  -> Payment calculation
  -> Invoice
  -> Receipt
```

Modul bisnis utama:

- `leads` dan `leads_sources`
- `students`
- `universities` dan `university_courses`
- `payments`, `payment_invoices`, `payment_receipts`, dan `payment_methods`
- `reports`
- `users`, `auth`, dan `geolocation`

Modul sistem:

- `apps_modules`
- `apps_module_features`
- `apps_permission_groups`
- `apps_menus`
- `dashboard`

## Pola aplikasi

- `check_auth()` menjaga controller yang memerlukan login.
- Permission aktif diperoleh melalui `feature_accessed()` dan diperiksa dengan `permit_check()`.
- User `DEVELOPER` memperoleh seluruh feature aktif.
- `checkPermission()` versi lama saat ini selalu menghasilkan `TRUE` dan bukan mekanisme permission utama.
- Mayoritas tabel memakai soft delete melalui `row_status` (`1` aktif, `0` recycle bin).
- Field audit umum: `created_by`, `updated_by`, `created_at`, dan `updated_at`.
- Model umumnya menerima operasi `GET`, `POST`, `PATCH`, dan `DELETE`, dengan opsi seperti `select`, `row_status`, `outputtype`, `order_by`, `limit`, dan `whereclause`.

## Database aktual

Database memiliki 27 tabel.

### Sistem dan akses

- `users`
- `user_profiles`
- `user_activity_logs`
- `sys_error_logs`
- `apps_configs`
- `apps_modules`
- `apps_module_features`
- `apps_permission_groups`
- `apps_permission_group_relations`
- `apps_menus`
- `apps_booked_number`

### Pendidikan dan CRM

- `leads`
- `leads_sources`
- `students`
- `universities`
- `university_courses`

### Keuangan

- `payments`
- `payment_invoices`
- `payment_receipts`
- `payment_methods`

### Geolocation

- `geo_countries`
- `geo_states`
- `geo_cities`
- `geo_districts`
- `geo_villages`
- `geo_postal_codes`

### Development

- `test_table`

## Relasi penting

```text
universities
  -> university_courses
    -> leads
      -> students
        -> payments
        -> payment_invoices
          -> payment_receipts

payment_methods
  -> payment_receipts
```

- `leads.university_id -> universities.id`
- `leads.course_id -> university_courses.id`
- `leads.assigned_to -> users.id`
- `students.enquiry_number -> leads.enquiry_number`
- `students.university_id -> universities.id`
- `students.course_id -> university_courses.id`
- `payments.student_number -> students.student_number`
- `payment_invoices.student_number -> students.student_number`
- `payment_receipts.student_number -> students.student_number`
- `payment_receipts.invoice_number -> payment_invoices.invoice_number`
- `payment_receipts.payment_method_id -> payment_methods.id`

Geolocation membentuk hierarki `countries -> states -> cities -> districts -> villages`. Lead, student, university, dan user profile menyimpan ID serta nama lokasi sebagai snapshot.

## Status Reports

Modul `reports` masih berupa prototipe:

- `reports/leads` menampilkan empat summary card dan satu ECharts bar chart.
- Nilai summary card dan chart masih hardcoded.
- Belum ada model atau endpoint data report.
- `reports/conselor` dan `reports/payment_receipt` masih kosong.
- Seri source pada chart menggunakan stack `Leads`; seri conversion/status menggunakan stack `Status`.

Tahap implementasi yang disarankan:

1. Tetapkan filter periode, universitas, course, source, counselor, dan status.
2. Buat `Reports_model` dengan query agregasi yang terparameterisasi.
3. Buat endpoint JSON khusus report.
4. Hubungkan summary card dan ECharts ke endpoint.
5. Implementasikan loading, empty state, error state, dan resize chart.
6. Tambahkan export bila definisi angka report sudah disepakati.

## Risiko dan utang teknis

- `db_history` hanya berisi perubahan terbaru, bukan skema lengkap atau migration yang dapat membangun database dari nol.
- Business key `enquiry_number`, `student_number`, `invoice_number`, dan `receipt_number` hanya memiliki indeks non-unique.
- Beberapa relasi menggunakan kode/string tanpa foreign key, misalnya `leads.source_code`.
- Mayoritas `created_by` dan `updated_by` tidak memiliki foreign key ke `users`.
- `leads_sources.password` perlu diaudit cara penyimpanan dan penggunaannya.
- Banyak query dibuat secara manual; escaping dan validasi input perlu diperiksa setiap kali diubah.
- `example` dan `test_table` tampaknya merupakan artefak development.

## Catatan perubahan lokal

Saat pemetaan dilakukan, worktree sudah berisi perubahan lokal dan file baru pada leads, students, payments, payment receipts, reports, serta beberapa asset. Perubahan tersebut dianggap milik pengembang dan tidak boleh ditimpa tanpa pemeriksaan diff terlebih dahulu.
