<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseShortcodeMappingResource\Pages;
use App\Models\CourseShortcodeMapping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseShortcodeMappingResource extends Resource
{
    protected static ?string $model = CourseShortcodeMapping::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Access Control';

    protected static ?string $navigationLabel = 'Course Shortcodes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('course_name')->required()->maxLength(255)->disabled(),
            Forms\Components\TextInput::make('short_code')->required()->maxLength(10),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_name')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('short_code')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseShortcodeMappings::route('/'),
            'edit' => Pages\EditCourseShortcodeMapping::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
