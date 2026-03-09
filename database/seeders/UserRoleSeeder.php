<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@tenstrings.org'],
            [
                'name' => 'System Admin',
                'phone' => '+2348000000000',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $instructorUser = User::query()->updateOrCreate(
            ['email' => 'instructor@tenstrings.org'],
            [
                'name' => 'Lead Instructor',
                'phone' => '+2348000000001',
                'role' => 'instructor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Instructor::query()->updateOrCreate(
            ['email' => $instructorUser->email],
            [
                'user_id' => $instructorUser->id,
                'first_name' => 'Lead',
                'last_name' => 'Instructor',
                'phone' => '+2348000000001',
                'specialization' => 'Piano, Voice',
                'is_active' => true,
            ]
        );

        $studentUser = User::query()->updateOrCreate(
            ['email' => 'student@tenstrings.org'],
            [
                'name' => 'Sample Student',
                'phone' => '+2348000000002',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Student::query()->updateOrCreate(
            ['email' => $studentUser->email],
            [
                'user_id' => $studentUser->id,
                'first_name' => 'Sample',
                'last_name' => 'Student',
                'phone' => '+2348000000002',
                'address' => 'Lagos, Nigeria',
                'registration_date' => now()->toDateString(),
                'status' => 'active',
            ]
        );

        $admin->refresh();
    }
}
