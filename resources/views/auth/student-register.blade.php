<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6f7fb; }
        .container { max-width: 850px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .full { grid-column: 1 / -1; }
        label { display:block; font-size: 14px; margin-bottom: 4px; color: #333; }
        input, textarea, button { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; }
        button { background: #111827; color: #fff; border: none; cursor: pointer; }
        .errors { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Registration</h1>
    <p>Complete your details to create a student account.</p>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('student.register.store') }}">
        @csrf
        <div class="grid">
            <div><label>First Name</label><input type="text" name="first_name" value="{{ old('first_name') }}" required></div>
            <div><label>Middle Name</label><input type="text" name="middle_name" value="{{ old('middle_name') }}"></div>
            <div><label>Last Name</label><input type="text" name="last_name" value="{{ old('last_name') }}" required></div>
            <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
            <div><label>Phone</label><input type="text" name="phone" value="{{ old('phone') }}" required></div>
            <div><label>Date of Birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"></div>
            <div class="full"><label>Address</label><textarea name="address">{{ old('address') }}</textarea></div>
            <div><label>Guardian Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name') }}"></div>
            <div><label>Guardian Phone</label><input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}"></div>
            <div><label>Guardian Email</label><input type="email" name="guardian_email" value="{{ old('guardian_email') }}"></div>
            <div><label>Relationship</label><input type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}"></div>
            <div><label>Password</label><input type="password" name="password" required></div>
            <div><label>Confirm Password</label><input type="password" name="password_confirmation" required></div>
            <div class="full"><button type="submit">Register</button></div>
        </div>
    </form>
</div>
</body>
</html>
