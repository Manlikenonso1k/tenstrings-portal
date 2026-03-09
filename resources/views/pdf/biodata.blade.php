<!doctype html>
<html>
<head><meta charset="utf-8"><title>Bio-data</title></head>
<body style="font-family: DejaVu Sans, sans-serif;">
    <h2>Tenstrings Portal - Student Bio-data</h2>
    <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
    <p><strong>Matric Number:</strong> {{ $student->student_number }}</p>
    <p><strong>Email:</strong> {{ $student->email }}</p>
    <p><strong>Phone:</strong> {{ $student->phone }}</p>
    <p><strong>Address:</strong> {{ $student->address }}</p>
    <p><strong>Guardian Phone:</strong> {{ $student->guardian_phone }}</p>
    <p><strong>Branch:</strong> {{ $student->branch }}</p>
    <p><strong>Course:</strong> {{ $student->selected_course_name }}</p>
</body>
</html>
