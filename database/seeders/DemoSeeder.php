<?php

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name'     => 'Demo Admin',
                'phone'    => '08000000001',
                'role'     => 'super_admin',
                'password' => Hash::make('Demo@1234'),
            ]
        );

        // 2. Accounts Clerk
        User::firstOrCreate(
            ['email' => 'clerk@demo.com'],
            [
                'name'     => 'Demo Clerk',
                'phone'    => '08000000002',
                'role'     => 'accounts_clerk',
                'password' => Hash::make('Demo@1234'),
            ]
        );

        // 3. Instructor user + Instructor record
        $instrUser = User::firstOrCreate(
            ['email' => 'instructor@demo.com'],
            [
                'name'     => 'Demo Instructor',
                'phone'    => '08000000003',
                'role'     => 'instructor',
                'password' => Hash::make('Demo@1234'),
            ]
        );

        Instructor::firstOrCreate(
            ['user_id' => $instrUser->id],
            [
                'instructor_number' => 'INS-DEMO-001',
                'first_name'        => 'Demo',
                'last_name'         => 'Instructor',
                'email'             => 'instructor@demo.com',
                'phone'             => '08000000003',
                'specialization'    => 'Music Production',
                'is_active'         => true,
            ]
        );

        // 4. Student user + Student record
        $stuUser = User::firstOrCreate(
            ['email' => 'student@demo.com'],
            [
                'name'     => 'Demo Student',
                'phone'    => '08000000004',
                'role'     => 'student',
                'password' => Hash::make('Demo@1234'),
            ]
        );

        Student::firstOrCreate(
            ['email' => 'student@demo.com'],
            [
                'user_id'              => $stuUser->id,
                'first_name'           => 'Demo',
                'last_name'            => 'Student',
                'phone'                => '08000000004',
                'branch'               => 'AJAH BRANCH',
                'selected_course_name' => 'Certificate in Voice',
                'selected_course_code' => 'CRS-VOICE',
                'duration'             => '6 months',
                'start_date'           => now()->toDateString(),
                'registration_date'    => now()->toDateString(),
                'fees_paid'            => 50000,
                'balance_due'          => 50000,
                'total_balance'        => 100000,
                'status'               => 'active',
            ]
        );

        $this->command->info('✅ Demo accounts created successfully!');
    }
}
