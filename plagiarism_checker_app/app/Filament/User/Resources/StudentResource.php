<?php

namespace App\Filament\User\Resources;

use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Components\Forms\TextInputRelationship;
use App\Filament\User\Resources\StudentResource\Pages;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Subject;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Filament\Tables\Filters\SelectFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Student Management';

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function canAccess(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
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

                SelectFilter::make('class_id')
                    ->label(__('Class'))
                    ->options(fn() => \App\Models\ClassRoom::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereHas('enrollments', function ($q) use ($data) {
                                $q->whereIn('class_id', $data['values']);
                            });
                        }
                    }),
                SelectFilter::make('subject_id')
                    ->label(__('Subject'))
                    ->options(fn() => \App\Models\Subject::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereHas('enrollments.class', function ($q) use ($data) {
                                $q->whereIn('subject_id', $data['values']);
                            });
                        }
                    }),
                DateRangeFilter::make('enrollment_date')
                    ->label(__('Enrollment Date Range'))
                    ->placeholder(__('Input date range')),
                ...BaseFilterGroup::show(),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make()->color('primary'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $assignedClassIds = auth()->user()->classrooms()->get()->pluck('id')->toArray();
        return Student::query()->whereHas('enrollments', function ($q) use ($assignedClassIds) {
            $q->whereIn('class_id', $assignedClassIds);
        });
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
