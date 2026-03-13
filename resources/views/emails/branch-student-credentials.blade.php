<p>Hello Team,</p>

<p>A student record was created or updated via CSV import.</p>

<p>
    <strong>Name:</strong> {{ $studentName }}<br>
    <strong>Matric Number:</strong> {{ $student->student_number }}<br>
    <strong>Email:</strong> {{ $student->email }}<br>
    <strong>Branch:</strong> {{ $student->branch ?: 'AJAH BRANCH' }}<br>
    <strong>Temporary Password:</strong> {{ $plainPassword }}
</p>

<p>
    Login URL: {{ url('/portal/login') }}
</p>

<p>Regards,<br>Tenstrings Portal</p>
