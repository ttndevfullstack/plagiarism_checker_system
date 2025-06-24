<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ExamResource\Pages;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Exam Management';

    protected static ?string $navigationIcon = 'heroicon-s-paper-airplane';

    public static function canCreate(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('class_id')
                ->label('Class')
                ->options(ClassRoom::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Select::make('teacher_id')
                ->label('Teacher')
                ->options(Teacher::all()->pluck('id', 'id'))
                ->required(),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->maxLength(1000),
            Forms\Components\DateTimePicker::make('start_time')
                ->label('Start Time'),
            Forms\Components\DateTimePicker::make('end_time')
                ->label('End Time'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('class.name')->label('Class')->sortable(),
                Tables\Columns\TextColumn::make('teacher.id')->label('Teacher'),
                Tables\Columns\TextColumn::make('start_time')->dateTime(),
                Tables\Columns\TextColumn::make('end_time')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(\App\Filament\Components\Filters\BaseFilterGroup::show())
            ->actions(
                auth()->user()->isStudent()
                    ? [
                        Actions\ViewAction::make(),
                    ]
                    : [
                        Actions\ViewAction::make(),
                        Actions\EditAction::make()->color(Color::Blue),
                        Actions\DeleteAction::make(),
                    ]
            );
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->exams();
    }

    /**
     * @return array<string, \Filament\Resources\Pages\Page> 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
            'view' => Pages\ViewExam::route('/{record}'),
        ];
    }
}
