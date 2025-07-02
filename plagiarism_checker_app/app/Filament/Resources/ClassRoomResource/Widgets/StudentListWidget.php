<?php

namespace App\Filament\Resources\ClassRoomResource\Widgets;

use App\Filament\Components\Filters\BaseFilterGroup;
use App\Filament\Resources\StudentResource;
use App\Models\ClassRoom;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class StudentListWidget extends TableWidget
{
    public ClassRoom $record;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Student::query()
                    ->whereHas('enrollments', function ($query) {
                        $query->where('class_id', $this->record->id);
                    })
                    ->with('user')
            )
            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn () => null),
                Tables\Actions\Action::make('createStudent')
                    ->label('Create Student')
                    ->form([
                        \Filament\Forms\Components\Section::make('User Information')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('first_name')
                                    ->label('First Name')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->required(),
                                \Filament\Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                \Filament\Forms\Components\DatePicker::make('dob')
                                    ->label('Date of Birth'),
                                \Filament\Forms\Components\TextInput::make('phone')
                                    ->label('Phone'),
                                \Filament\Forms\Components\TextInput::make('address')
                                    ->label('Address'),
                            ]),

                        \Filament\Forms\Components\Section::make('Student Details')
                            ->schema([
                                \Filament\Forms\Components\DateTimePicker::make('enrollment_date')
                                    ->label('Enrollment Date')
                                    ->default(now())
                                    ->required(),
                                \Filament\Forms\Components\Hidden::make('class_id')->default($this->record->id),
                            ]),
                    ])
                    ->action(function (array $data) {
                        // Create User and Student
                        $userData = [
                            'first_name' => $data['first_name'] ?? null,
                            'last_name' => $data['last_name'] ?? null,
                            'email' => $data['email'] ?? null,
                            'dob' => $data['dob'] ?? null,
                            'phone' => $data['phone'] ?? null,
                            'address' => $data['address'] ?? null,
                            'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        ];
                        $studentData = [
                            'enrollment_date' => $data['enrollment_date'] ?? now(),
                        ];
                        $classId = $data['class_id'];

                        $student = \Illuminate\Support\Facades\DB::transaction(function () use ($userData, $studentData) {
                            $user = \App\Models\User::create($userData);
                            $user->assignRole(\App\Models\User::STUDENT_ROLE);

                            $student = new \App\Models\Student($studentData);
                            $student->user()->associate($user);
                            $student->save();

                            return $student;
                        });

                        // Create Enrollment
                        \App\Models\Enrollment::create([
                            'student_id' => $student->id,
                            'class_id' => $classId,
                            'enrollment_date' => $student->enrollment_date,
                        ]);
                    })
                    ->icon('heroicon-m-plus')
                    ->visible(fn() => auth()->user()->isAdmin())
            ])
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
            ->actions(auth()->user()->isAdmin() ? [
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => StudentResource::getUrl('view', ['record' => $record->getKey()])),
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => StudentResource::getUrl('edit', ['record' => $record->getKey()])),
                Tables\Actions\DeleteAction::make(),
            ] : [
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => StudentResource::getUrl('view', ['record' => $record->getKey()])),
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => StudentResource::getUrl('edit', ['record' => $record->getKey()])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
