<?php

namespace App\Filament\Portal\Pages;

use App\Models\Course;
use App\Models\Enrollment;
use App\Support\EnrollmentLimitService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class CourseRegistrationPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'COURSE REGISTRATION';

    protected static string $view = 'filament.portal.pages.course-registration-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'intake_month' => 'FEBRUARY',
            'start_date' => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Select::make('intake_month')
                    ->options([
                        'FEBRUARY' => 'FEBRUARY',
                        'MAY' => 'MAY',
                        'AUGUST' => 'AUGUST',
                        'NOVEMBER' => 'NOVEMBER',
                    ])
                    ->required(),
                DatePicker::make('start_date')->required(),
                Select::make('course_ids')
                    ->label('Courses')
                    ->options(Course::query()->where('is_active', true)->pluck('name', 'id')->all())
                    ->multiple()
                    ->required()
                    ->maxItems(2),
            ]);
    }

    public function registerCourses(): void
    {
        $state = $this->form->getState();
        $student = auth()->user()?->student;

        if (! $student) {
            throw ValidationException::withMessages([
                'data.course_ids' => 'Student profile not found.',
            ]);
        }

        $courseIds = collect($state['course_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values()->all();

        if (! EnrollmentLimitService::canEnrollInCourses($student->id, $courseIds)) {
            throw ValidationException::withMessages([
                'data.course_ids' => 'You cannot register more than 2 ongoing courses.',
            ]);
        }

        $maxDuration = (int) Course::query()->whereIn('id', $courseIds)->max('duration_months');
        $startDate = $state['start_date'];

        $enrollment = Enrollment::query()->create([
            'student_id' => $student->id,
            'enrollment_date' => now()->toDateString(),
            'intake_month' => $state['intake_month'],
            'start_date' => $startDate,
            'expected_end_date' => now()->parse($startDate)->addMonths(max(1, $maxDuration))->toDateString(),
            'status' => 'ongoing',
            'notes' => 'Registered by student via portal',
        ]);

        $enrollment->courses()->sync($courseIds);

        Notification::make()
            ->title('Course registration submitted')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        $studentId = auth()->user()?->student?->id;

        return [
            'recentEnrollments' => Enrollment::query()
                ->where('student_id', $studentId)
                ->with('courses')
                ->latest()
                ->limit(8)
                ->get(),
        ];
    }
}
