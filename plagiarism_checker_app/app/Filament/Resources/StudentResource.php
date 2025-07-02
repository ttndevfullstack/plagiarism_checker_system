<?php

namespace App\Filament\Resources;

use App\Filament\Components\Actions\BaseActionGroup;
use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Components\Forms\TextInputRelationship;
use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Student Management';

    protected static ?string $navigationIcon = 'heroicon-c-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        TextInputRelationship::show('first_name', __('First Name'), 'user')->required(),
                        TextInputRelationship::show('last_name', __('Last Name'), 'user')->required(),
                        TextInputRelationship::show('email', __('Email'), 'user')->email()->required(),
                        DatePicker::make('dob')->label('Date of Birth')->required(false),
                        TextInput::make('phone')->label('Phone')->maxLength(20)->tel()->required(false),
                        TextInput::make('address')->label('Address')->maxLength(255)->required(false),
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
                Tables\Columns\TextColumn::make('student_code')
                    ->label(__('Student Code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label(__('Full Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.dob')
                    ->label('Date of Birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.address')
                    ->label('Address')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                ...BaseFilterGroup::show(),
                DateRangeFilter::make('enrollment_date')
                    ->label(__('Enrollment Date Range'))
                    ->placeholder(__('Input date range')),
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
