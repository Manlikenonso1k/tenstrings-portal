<?php
// TEMPORARY PASSWORD RESET SCRIPT — DELETE IMMEDIATELY AFTER USE
$secret = 'ts2026reset';

if (($_GET['token'] ?? '') !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('role', 'super_admin')->first();
if (!$user) {
    die('No super_admin user found.');
}

$newPassword = 'Admin@Tenstrings2026!';
$user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
$user->save();

echo 'Password reset OK. Email: ' . $user->email . ' | New password: ' . $newPassword;
echo '<br><br><strong>DELETE THIS FILE NOW: public/reset-pass.php</strong>';
