<?php

namespace App\Filament\Resources;

use App\Models\Teacher;
use App\Filament\Components\Actions\BaseActionGroup;
use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Components\Forms\TextInputRelationship;
use App\Filament\Resources\TeacherResource\Pages;
use App\Models\ClassRoom;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Filament\Tables\Filters\SelectFilter;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Teacher Management';

    protected static ?string $navigationIcon = 'heroicon-c-academic-cap';

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
                        TextInputRelationship::show('phone', __('Phone'), 'user')->label('Phone')->maxLength(20)->tel()->required(false),
                        TextInputRelationship::show('address', __('Address'), 'user')->label('Address')->maxLength(255)->required(false),
                    ]),

                Forms\Components\Section::make('Teacher Details')
                    ->schema([
                        Forms\Components\DateTimePicker::make('joined_date')
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
                Tables\Columns\TextColumn::make('joined_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('class_id')
                    ->label(__('Class'))
                    ->options(fn () => ClassRoom::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->query(function ($query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereHas('classes', function ($q) use ($data) {
                                $q->whereIn('id', $data['values']);
                            });
                        }
                    }),
                SelectFilter::make('subject_id')
                    ->label(__('Subject'))
                    ->options(fn () => Subject::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->query(function ($query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereHas('classes', function ($q) use ($data) {
                                $q->whereIn('subject_id', $data['values']);
                            });
                        }
                    }),
                DateRangeFilter::make('joined_date')
                    ->label(__('Joined Date Range'))
                    ->placeholder(__('Input date range')),
                ...BaseFilterGroup::show(),
            ])
            ->actions([
                ...BaseActionGroup::show(),

                Action::make('assignClasses')
                    ->color(Color::Green)
                    ->label(__('Assign Classes'))
                    ->icon('heroicon-s-clipboard-document-check')
                    ->modalHeading(__('Assign Classes to Teacher'))
                    ->modalButton(__('Assign'))
                    ->form([
                        Forms\Components\Select::make('class_ids')
                            ->label(__('Select Classes'))
                            ->options(
                                ClassRoom::all()->mapWithKeys(static function ($class) {
                                    return [
                                        $class->id => sprintf(
                                            'Room Number: %s | Name: %s | Teacher: %s | Total Students: %s',
                                            $class->room_number,
                                            $class->name,
                                            optional($class->teacher->user)->full_name,
                                            $class->enrollments()->count()
                                        ),
                                    ];
                                })
                            )
                            ->default(static fn($record) => $record->classes->pluck('id')->toArray())
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])
                    ->action(static function (array $data, Teacher $record): void {
                        ClassRoom::whereIn('id', $data['class_ids'])->update(['teacher_id' => $record->id]);
                    })
                    ->successNotificationTitle(__('Classes assigned successfully!')),
            ])
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view' => Pages\ViewTeacher::route('/{record}'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
