<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportStudentsCsv extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationGroup = 'Data Tools';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Import Students CSV';

    protected static ?string $slug = 'import-students-csv';

    protected static string $view = 'filament.pages.import-students-csv';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'send_email' => false,
            'only_branch' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('CSV Import Options')
                    ->description('Upload the student CSV and run the import command.')
                    ->schema([
                        FileUpload::make('csv_path')
                            ->label('Student CSV File')
                            ->disk('local')
                            ->directory('imports')
                            ->acceptedFileTypes([
                                'text/csv',
                                'text/plain',
                                'application/vnd.ms-excel',
                            ])
                            ->required()
                            ->helperText('Supports .csv files. The file will be stored under storage/app/private/imports.'),
                        Select::make('only_branch')
                            ->label('Only Branch (optional)')
                            ->options([
                                'AJAH' => 'AJAH',
                                'FESTAC' => 'FESTAC',
                                'IKEJA' => 'IKEJA',
                                'AGEGE' => 'AGEGE',
                            ])
                            ->placeholder('Import all branches'),
                        Toggle::make('send_email')
                            ->label('Send credentials by email')
                            ->helperText('Off by default. Enable only when you want immediate email delivery.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $state = $this->form->getState();
        $csvPath = (string) ($state['csv_path'] ?? '');

        if ($csvPath === '') {
            Notification::make()
                ->title('CSV file is required.')
                ->danger()
                ->send();

            return;
        }

        $absolutePath = Storage::disk('local')->path($csvPath);

        if (! is_file($absolutePath)) {
            Notification::make()
                ->title('Uploaded file was not found on disk.')
                ->body($absolutePath)
                ->danger()
                ->send();

            return;
        }

        $arguments = ['file' => $absolutePath];

        if (! (bool) ($state['send_email'] ?? true)) {
            $arguments['--no-email'] = true;
        }

        $onlyBranch = strtoupper(trim((string) ($state['only_branch'] ?? '')));
        if ($onlyBranch !== '') {
            $arguments['--only-branch'] = $onlyBranch;
        }

        try {
            Artisan::call('students:import-csv', $arguments);
            $output = trim(Artisan::output());

            Notification::make()
                ->title('Student import completed.')
                ->body($output !== '' ? $output : 'Import command finished successfully.')
                ->success()
                ->send();
        } catch (Throwable $exception) {
            Notification::make()
                ->title('Student import failed.')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user && in_array($user->role, ['super_admin', 'admin'], true);
    }
}
