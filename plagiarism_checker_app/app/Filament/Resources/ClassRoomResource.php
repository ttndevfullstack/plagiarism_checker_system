<?php

namespace App\Filament\Resources;

use App\Filament\Components\Forms\Selectors\StudentSelector;
use App\Filament\Components\Forms\Selectors\TeacherSelector;
use App\Filament\Components\Forms\Selectors\SubjectSelector;
use App\Filament\Resources\ClassRoomResource\Pages;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Class Room Management';

    protected static ?string $navigationIcon = 'heroicon-s-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                    ->required()
                    ->disabled()
                    ->hidden(static fn ($livewire) => $livewire instanceof CreateRecord),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                SubjectSelector::show(),

                TeacherSelector::show(),

                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->native(false)
                    ->closeOnDateSelection(),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->native(false)
                    ->closeOnDateSelection()
                    ->after('start_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room_number')
                    ->label('Room Number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Class Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(static fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(static fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('teacher.user.fullname')
                    ->label('Teacher Name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(\App\Filament\Components\Filters\BaseFilterGroup::show())
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ...\App\Filament\Components\Actions\BaseActionGroup::show(),

                    Tables\Actions\Action::make('action_assign_subject')
                        ->icon('heroicon-s-book-open')
                        ->color(Color::Green)
                        ->label(__('Assign Subject'))
                        ->modalIcon('heroicon-o-book-open')
                        ->modalHeading('Assign Subjects')
                        ->modalButton(__('Assign'))
                        ->form([
                            SubjectSelector::show()
                                ->default(static fn ($record) => $record->subject_id),
                        ])
                        ->action(static function (array $data, ClassRoom $record): void {
                            try {
                                $record->subject_id = $data['subject_id'];
                                $record->save();

                                Notification::make()
                                    ->title(__('Assigned successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title(__('Assigned failure'))
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->successNotificationTitle(__('Classes assigned successfully!')),

                    Tables\Actions\Action::make('action_assign_teacher')
                        ->icon('heroicon-s-academic-cap')
                        ->color(Color::Green)
                        ->label(__('Assign Teacher'))
                        ->modalIcon('heroicon-o-academic-cap')
                        ->modalHeading('Assign Teachers')
                        ->modalButton(__('Assign'))
                        ->form([
                            TeacherSelector::show()
                                ->totalAssignedClassLabel()
                                ->default(static fn ($record) => $record->teacher_id),
                        ])
                        ->action(static function (array $data, ClassRoom $record): void {
                            try {
                                $record->teacher_id = $data['teacher_id'];
                                $record->save();

                                Notification::make()
                                    ->title(__('Assigned successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title(__('Assigned failure'))
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->successNotificationTitle(__('Classes assigned successfully!')),

                    Tables\Actions\Action::make('action_assign_students')
                        ->icon('heroicon-s-users')
                        ->color(Color::Green)
                        ->label(__('Assign Students'))
                        ->modalIcon('heroicon-o-users')
                        ->modalHeading('Assign Students')
                        ->modalButton(__('Assign'))
                        ->form([
                            StudentSelector::show()
                                ->multiple()
                                ->default(static fn ($record) => $record->enrollments()->with('student')->get()->pluck('student.id')->toArray()),
                        ])
                        ->action(static function (array $data, ClassRoom $record): void {
                            try {
                                $studentIds = $data['student_id'] ?? [];
                                $record->enrollments()->whereNotIn('student_id', $studentIds)->delete();

                                $newEnrollments = collect($studentIds)->map(static fn ($id) => [
                                    'student_id' => $id,
                                    'class_id' => $record->id,
                                    'enrollment_date' => now(),
                                ])->toArray();

                                Enrollment::upsert($newEnrollments, ['student_id', 'class_id']);

                                Notification::make()
                                    ->title(__('Assigned successfully'))
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title(__('Assigned failure'))
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])->iconButton(),
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
            'index' => Pages\ListClassRooms::route('/'),
            'create' => Pages\CreateClassRoom::route('/create'),
            'view' => Pages\ViewClassRoom::route('/{record}'),
            'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }
}
