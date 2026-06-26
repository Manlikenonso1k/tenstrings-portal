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
            ['name' => 'Advanced Diploma in Music Performance', 'duration_months' => 18, 'duration_label' => '18 months', 'course_fee' => 1800000],
            ['name' => 'Advanced Diploma in Music Production', 'duration_months' => 18, 'duration_label' => '18 months', 'course_fee' => 1800000],
            ['name' => 'Diploma in Music Performance', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 537000],
            ['name' => 'Diploma in Music Production', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 537000],
            ['name' => 'Certificate in Music Performance', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 350000],
            ['name' => 'Certificate in Music Production', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 350000],
            ['name' => 'Certificate in Gospel Music Performance', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 250000],
            ['name' => 'Certificate in Gospel Music Performance', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 350000],
            ['name' => 'Certificate in Piano', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Piano', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
            ['name' => 'Certificate in Guitar', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Guitar', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
            ['name' => 'Certificate in Drums', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Drums', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
            ['name' => 'Certificate in Voice', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Voice', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
            // Keeping others for completeness with catalog
            ['name' => 'Diploma in Gospel Music Performance', 'duration_months' => 12, 'duration_label' => '1 year', 'course_fee' => 537000],
            ['name' => 'Certificate in Songwriting', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Songwriting', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
            ['name' => 'Certificate in Music Business', 'duration_months' => 3, 'duration_label' => '3 months', 'course_fee' => 180000],
            ['name' => 'Certificate in Music Business', 'duration_months' => 6, 'duration_label' => '6 months', 'course_fee' => 300000],
        ];

        foreach ($courses as $course) {
            Course::query()->updateOrCreate(
                [
                    'name' => $course['name'],
                    'duration_months' => $course['duration_months'],
                ],
                [
                    'duration_label' => $course['duration_label'],
                    'course_fee' => $course['course_fee'],
                    'description' => $course['name'] . ' program.',
                    'max_students_per_class' => 30,
                    'is_active' => true,
                ]
            );
        }
    }
}
