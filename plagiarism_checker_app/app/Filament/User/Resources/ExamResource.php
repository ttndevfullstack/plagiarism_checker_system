<?php

namespace App\Filament\User\Resources;

use App\Enums\DocumentStatus;
use App\Filament\User\Resources\ExamResource\Pages;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Document;
use App\Models\Teacher;
use Awcodes\Curator\Components\Forms\CuratorPicker;
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
                        Actions\Action::make('submitDocument')
                            ->label('Submit Document')
                            ->icon('heroicon-c-paper-airplane')
                            ->color(Color::Green)
                            ->form([
                                CuratorPicker::make('media_id')
                                    ->label('Document File')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    ])
                                    ->maxSize(30720000)
                                    ->preserveFilenames()
                                    ->required(),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->maxLength(1000)
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->action(function (array $data, $record) {
                                $user = auth()->user();
                                $exam = $record;
                                $mediaId = $data['media_id'];
                                $description = $data['description'] ?? null;
                                $originalName = \Awcodes\Curator\Models\Media::find($mediaId)?->name ?? null;

                                // Create document
                                Document::create([
                                    'media_id'      => $mediaId,
                                    'exam_id'       => $exam->id,
                                    'class_id'      => $exam->class_id,
                                    'uploaded_by'   => $user->id,
                                    'original_name' => $originalName,
                                    'description'   => $description,
                                    'status'        => DocumentStatus::PENDING,
                                ]);

                                // Dispatch check document
                            })
                            ->visible(fn () => auth()->user()->isStudent())
                            ->modalHeading('Submit Document for Exam')
                            ->modalButton('Submit')
                            ->successNotificationTitle('Document submitted successfully.'),
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
        if (auth()->user()->isTeacher()) {
            return Exam::query()->where('uploaded_by', auth()->user()->id);
        }

        $classRoomIds = auth()->user()->classrooms()->get()->pluck('id')->toArray();
        return Exam::query()->whereIn('class_id', $classRoomIds)->where('end_time', '>=', now());
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
