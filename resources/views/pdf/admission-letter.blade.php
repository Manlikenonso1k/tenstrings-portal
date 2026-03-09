<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admission Letter</title></head>
<body style="font-family: DejaVu Sans, sans-serif;">
    <h2>Tenstrings Portal - Admission Letter</h2>
    <p>Dear {{ $student->first_name }} {{ $student->last_name }},</p>
    <p>Congratulations! You have been admitted.</p>
    <p><strong>Matric Number:</strong> {{ $student->student_number }}</p>
    <p><strong>Course:</strong> {{ $student->selected_course_name }}</p>
    <p><strong>Branch:</strong> {{ $student->branch }}</p>
    <p><strong>Registration Date:</strong> {{ $student->registration_date }}</p>
</body>
</html>
