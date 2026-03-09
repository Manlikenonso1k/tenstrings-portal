<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['name' => 'Advanced Diploma in Music Performance', 'duration_months' => 18, 'duration_label' => '18 months', 'course_fee' => 180000],
            ['name' => 'Advanced Diploma in Music Production', 'duration_months' => 18, 'duration_label' => '18 months', 'course_fee' => 180000],
            ['name' => 'Diploma in Music Performance', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 120000],
            ['name' => 'Diploma in Music Production', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 120000],
            ['name' => 'Certificate in Music Performance', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 80000],
            ['name' => 'Certificate in Music Production', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 80000],
            ['name' => 'Certificate in Gospel Music Performance', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 50000],
            ['name' => 'Certificate in Gospel Music Performance', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 80000],
            ['name' => 'Diploma in Gospel Music Performance', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 120000],
            ['name' => 'Certificate in Songwriting', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 45000],
            ['name' => 'Certificate in Songwriting', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 75000],
            ['name' => 'Certificate in Piano', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 45000],
            ['name' => 'Certificate in Piano', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 75000],
            ['name' => 'Certificate in Music Business', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 40000],
            ['name' => 'Certificate in Music Business', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 70000],
            ['name' => 'Certificate in Guitar', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 45000],
            ['name' => 'Certificate in Guitar', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 75000],
            ['name' => 'Certificate in Drums', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 45000],
            ['name' => 'Certificate in Drums', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 75000],
            ['name' => 'Certificate in Voice', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 45000],
            ['name' => 'Certificate in Voice', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 75000],
        ];

        foreach ($courses as $course) {
            Course::query()->create([
                ...$course,
                'description' => $course['name'] . ' program.',
                'max_students_per_class' => 30,
                'is_active' => true,
            ]);
        }
    }
}
