<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditStudentCore extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.edit-student-core';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->required()->maxLength(255),
            Forms\Components\TextInput::make('middle_name')->maxLength(255),
            Forms\Components\TextInput::make('last_name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->tel()->required()->maxLength(30),
            Forms\Components\TextInput::make('guardian_phone')->tel()->maxLength(30),
            Forms\Components\TextInput::make('guardian_name')->maxLength(255),
            Forms\Components\TextInput::make('guardian_email')->email()->maxLength(255),
            Forms\Components\TextInput::make('guardian_relationship')->maxLength(255),
            Forms\Components\Textarea::make('address')->rows(3),
            Forms\Components\Select::make('sex')
                ->options([
                    'Male' => 'Male',
                    'Female' => 'Female',
                ])
                ->native(false),
            Forms\Components\Select::make('branch')
                ->options([
                    'AJAH BRANCH' => 'AJAH BRANCH',
                    'AGEGE BRANCH' => 'AGEGE BRANCH',
                    'IKEJA BRANCH' => 'IKEJA BRANCH',
                    'FESTAC BRANCH' => 'FESTAC BRANCH',
                ])
                ->required(),
            Forms\Components\DatePicker::make('date_of_birth')->label('Date of Birth'),
            Forms\Components\Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'graduated' => 'Graduated',
                ])
                ->required(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_hub')
                ->label('Back to Hub')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => ViewStudent::getUrl(['record' => $this->record->getRouteKey()])),
        ];
    }
}
