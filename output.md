[u519226541@us-phx-web940 portal_app]$ git pull origin main
remote: Enumerating objects: 19, done.
remote: Counting objects: 100% (19/19), done.
remote: Compressing objects: 100% (4/4), done.
remote: Total 11 (delta 6), reused 11 (delta 6), pack-reused 0 (from 0)
Unpacking objects: 100% (11/11), 3.29 KiB | 674.00 KiB/s, done.
From https://github.com/Manlikenonso1k/tenstrings-portal
 * branch            main       -> FETCH_HEAD
   cdd0850..fe6c549  main       -> origin/main
Updating cdd0850..fe6c549
Fast-forward
 app/Console/Commands/DeletePaymentAndReconcile.php | 100 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 app/Models/Payment.php                             |  47 +++++++++++++++++++++++++++++++++++++++
 tests/Feature/PaymentDeletionCommandTest.php       |  83 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 3 files changed, 230 insertions(+)
 create mode 100644 app/Console/Commands/DeletePaymentAndReconcile.php
 create mode 100644 tests/Feature/PaymentDeletionCommandTest.php
[u519226541@us-phx-web940 portal_app]$ php artisan migrate --force

   INFO  Nothing to migrate.  

[u519226541@us-phx-web940 portal_app]$ php artisan tinker --execute='$payment = App\Models\Payment::where("payment_number", "PAY-00128")->orWhere("receipt_number", "REC-C5BB88E9")->with(["student", "course"])->get(); dump($payment->toArray());'
array:1 [
  0 => array:24 [
    "id" => 128
    "user_id" => 395
    "invoice_id" => null
    "gateway" => "manual"
    "reference" => "MANUAL-AB41A24088CA"
    "amount" => "1800000.00"
    "status" => "success"
    "gateway_response" => null
    "metadata" => null
    "processed_at" => "2026-10-09T13:02:17.000000Z"
    "payment_number" => "PAY-00128"
    "student_id" => 107
    "course_id" => 2
    "amount_paid" => "1800000.00"
    "payment_date" => "2026-10-09T00:00:00.000000Z"
    "payment_method" => "transfer"
    "receipt_number" => "REC-C5BB88E9"
    "payment_status" => "paid"
    "notes" => "test"
    "receipt_evidence_path" => "payments/evidence/01M4GC3P2W38CZHH8X9K69EQ32.pdf"
    "created_at" => "2026-10-09T13:02:17.000000Z"
    "updated_at" => "2026-10-09T13:02:17.000000Z"
    "student" => array:35 [
      "id" => 107
      "user_id" => 412
      "student_number" => "202611CRS-0002001"
      "selected_course_name" => "Advanced Diploma in Music Production"
      "selected_course_code" => "CRS-0002"
      "duration" => "18 months"
      "fees_paid" => "3600000.00"
      "balance_due" => "0.00"
      "hostel_fee" => "0.00"
      "total_balance" => "1800000.00"
      "first_name" => "Daniel"
      "middle_name" => null
      "last_name" => "Adelemoni"
      "email" => "adelemonidaniel@gmail.com"
      "phone" => "08140546733"
      "address" => """
        Road 10, plot 5 goodnews estate\n
        \n
        Guardian: Major L. Farayola str.
        """
      "branch" => "AJAH BRANCH"
      "photo_path" => null
      "avatar_url" => "students/passport/01M4G2QV3H9CWZ9KG3RZNWQAY4.jpeg"
      "birth_certificate_path" => null
      "jamb_path" => null
      "neco_path" => "students/documents/01M4G2QV3KNDA0DFZG3FTB84PN.jpeg"
      "waec_path" => null
      "date_of_birth" => "1996-09-16T00:00:00.000000Z"
      "sex" => "Male"
      "start_date" => "2026-11-14T00:00:00.000000Z"
      "registration_date" => "2026-11-13T00:00:00.000000Z"
      "status" => "active"
      "created_via" => "dashboard"
      "guardian_name" => "Monday Stephen"
      "guardian_phone" => "08178061405"
      "guardian_email" => null
      "guardian_relationship" => "Brother"
      "created_at" => "2026-10-09T10:18:31.000000Z"
      "updated_at" => "2026-10-09T13:02:17.000000Z"
    ]
    "course" => array:11 [
      "id" => 2
      "code" => "CRS-0002"
      "name" => "Advanced Diploma in Music Production"
      "duration_months" => 18
      "duration_label" => "18 months"
      "course_fee" => "1800000.00"
      "description" => "Advanced Diploma in Music Production program."
      "max_students_per_class" => 3000
      "is_active" => true
      "created_at" => "2026-03-11T10:48:57.000000Z"
      "updated_at" => "2026-07-23T17:40:45.000000Z"
    ]
  ]
] // vendor/psy/psysh/src/ExecutionClosure.php(41) : eval()'d code:2
[u519226541@us-phx-web940 portal_app]$ php artisan payments:delete-and-reconcile PAYMENT_ID
Payment was not found.
[u519226541@us-phx-web940 portal_app]$ php artisan payments:delete-and-reconcile PAYMENT_ID --confirm
Payment was not found.
[u519226541@us-phx-web940 portal_app]$ 