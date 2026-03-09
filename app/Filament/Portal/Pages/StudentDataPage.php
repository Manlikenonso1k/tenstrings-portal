<?php

namespace App\Filament\Portal\Pages;

use App\Models\Grade;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StudentDataPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'STUDENT DATA';

    protected static string $view = 'filament.portal.pages.student-data-page';

    public ?array $data = [];

    public function mount(): void
    {
        $student = auth()->user()?->student;

        $this->form->fill($student?->only([
            'avatar_url',
            'address',
            'phone',
            'guardian_phone',
            'birth_certificate_path',
            'jamb_path',
            'neco_path',
            'waec_path',
        ]) ?? []);
    }

    public function form(Form $form): Form
    {
        $student = auth()->user()?->student;

        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\Section::make('IDENTITY & PASSPORT')
                        ->schema([
                            Forms\Components\FileUpload::make('avatar_url')
                                ->label('Update Passport')
                                ->image()
                                ->disk('public_uploads')
                                ->directory('students/passport')
                                ->maxSize(2048)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
                            Forms\Components\Placeholder::make('student_number')->label('Matric Number')->content($student?->student_number ?? '-'),
                        ]),
                    Forms\Components\Section::make('CORE INFORMATION')
                        ->schema([
                            Forms\Components\Placeholder::make('full_name')->label('Full Name')->content(trim(($student?->first_name ?? '') . ' ' . ($student?->last_name ?? ''))),
                            Forms\Components\Textarea::make('address')->label('Address'),
                            Forms\Components\Placeholder::make('email')->label('Email')->content($student?->email ?? '-'),
                            Forms\Components\TextInput::make('phone')->label('Phone Number'),
                            Forms\Components\TextInput::make('guardian_phone')->label('Guardian Phone Number'),
                        ]),
                    Forms\Components\Section::make('ACADEMIC & PROGRESS')
                        ->schema([
                            Forms\Components\Placeholder::make('feb')->label('FEBRUARY')->content($this->quarterlyStat('FEBRUARY')),
                            Forms\Components\Placeholder::make('may')->label('MAY')->content($this->quarterlyStat('MAY')),
                            Forms\Components\Placeholder::make('aug')->label('AUGUST')->content($this->quarterlyStat('AUGUST')),
                            Forms\Components\Placeholder::make('nov')->label('NOVEMBER')->content($this->quarterlyStat('NOVEMBER')),
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('print_admission')
                                    ->label('Print Admission Letter')
                                    ->url($student ? route('students.print.admission_letter', $student) : null)
                                    ->openUrlInNewTab(),
                                Forms\Components\Actions\Action::make('print_biodata')
                                    ->label('Print Bio-data')
                                    ->url($student ? route('students.print.biodata', $student) : null)
                                    ->openUrlInNewTab(),
                            ]),
                        ]),
                    Forms\Components\Section::make('DOCUMENT VAULT')
                        ->schema([
                            Forms\Components\FileUpload::make('birth_certificate_path')
                                ->label('Birth Certificate')
                                ->disk('public_uploads')
                                ->directory('students/documents')
                                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('jamb_path')
                                ->label('JAMB')
                                ->disk('public_uploads')
                                ->directory('students/documents')
                                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('neco_path')
                                ->label('NECO')
                                ->disk('public_uploads')
                                ->directory('students/documents')
                                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('waec_path')
                                ->label('WAEC')
                                ->disk('public_uploads')
                                ->directory('students/documents')
                                ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'])
                                ->maxSize(2048),
                        ]),
                ]),
            ]);
    }

    public function save(): void
    {
        $student = auth()->user()?->student;

        if (! $student) {
            return;
        }

        $student->update($this->form->getState());

        Notification::make()->title('Student data updated')->success()->send();
    }

    private function quarterlyStat(string $month): string
    {
        $studentId = auth()->user()?->student?->id;

        if (! $studentId) {
            return '-';
        }

        $query = Grade::query()->where('student_id', $studentId)->where('assessment_month', $month);
        $count = $query->count();

        if ($count === 0) {
            return 'No assessment yet';
        }

        return $count . ' assessments | Avg: ' . round((float) $query->avg('percentage'), 2) . '%';
    }
}
