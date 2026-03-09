<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('student_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('registration_date');
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active');
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->timestamps();
        });

        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('instructor_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('specialization')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('duration_months');
            $table->string('duration_label');
            $table->decimal('course_fee', 12, 2);
            $table->text('description')->nullable();
            $table->unsignedInteger('max_students_per_class')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('course_instructor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'instructor_id']);
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('enrollment_number')->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('enrollment_date');
            $table->date('start_date');
            $table->date('expected_end_date');
            $table->enum('status', ['ongoing', 'completed', 'dropped'])->default('ongoing');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('enrollment_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->unique(['enrollment_id', 'course_id']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->text('instructor_notes')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'course_id', 'attendance_date']);
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('assessment_type', ['quiz', 'exam', 'practical', 'final']);
            $table->decimal('score', 8, 2);
            $table->decimal('maximum_score', 8, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade_letter', 2);
            $table->date('date_recorded');
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->decimal('maximum_score', 8, 2)->default(100);
            $table->timestamps();
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->dateTime('submission_date')->nullable();
            $table->string('file_path')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'submitted', 'graded'])->default('pending');
            $table->timestamps();
            $table->unique(['assignment_id', 'student_id']);
        });

        Schema::create('student_course_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_course_fee', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('outstanding_balance', 12, 2);
            $table->string('payment_plan')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['paid', 'partial', 'pending'])->default('pending');
            $table->timestamps();
            $table->unique(['student_id', 'course_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount_paid', 12, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'cheque']);
            $table->string('receipt_number')->nullable()->unique();
            $table->enum('payment_status', ['paid', 'partial', 'pending'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->enum('target_role', ['all', 'admin', 'instructor', 'student'])->default('all');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('event_type', ['class', 'assignment', 'payment', 'other']);
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('issue_date');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('student_course_fees');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('enrollment_course');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('course_instructor');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('instructors');
        Schema::dropIfExists('students');
    }
};
