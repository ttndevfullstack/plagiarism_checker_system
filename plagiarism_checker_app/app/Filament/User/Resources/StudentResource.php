<?php

namespace App\Filament\User\Resources;

use App\Filament\Components\Actions\BaseActionGroup;
use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Components\Forms\TextInputRelationship;
use App\Filament\User\Resources\StudentResource\Pages;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Student Management';

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function canAccess(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        TextInputRelationship::show('first_name', __('First Name'), 'user')->required(),
                        TextInputRelationship::show('last_name', __('Last Name'), 'user')->required(),
                        TextInputRelationship::show('email', __('Email'), 'user')->email()->required(),
                    ]),

                Forms\Components\Section::make('Student Details')
                    ->schema([
                        Forms\Components\DateTimePicker::make('enrollment_date')
                            ->default(now())
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label(__('Full Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_code')
                    ->label(__('Student Code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                DateRangeFilter::make('enrollment_date')
                    ->label(__('Enrollment Date Range'))
                    ->placeholder(__('Input date range')),
                ...BaseFilterGroup::show(),
            ])
            ->actions(BaseActionGroup::show())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, \Filament\Resources\Pages\Page> 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
