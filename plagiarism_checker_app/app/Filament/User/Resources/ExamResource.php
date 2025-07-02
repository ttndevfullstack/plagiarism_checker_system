<?php

namespace App\Filament\User\Resources;

use App\Enums\DocumentStatus;
use App\Filament\User\Resources\ExamResource\Pages;
use App\Jobs\ProcessSubmitDocument;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Document;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?int $navigationSort = 5;

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
                ->required()
                ->default(request()->get('class_id')),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->maxLength(1000),
            Forms\Components\DateTimePicker::make('start_time')
                ->default(now())
                ->required()
                ->label('Start Time'),
            Forms\Components\DateTimePicker::make('end_time')
                ->default(now()->addDays(3))
                ->required()
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
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->color(function ($record) {
                        if (! $record->end_time) {
                            return null;
                        }

                        $endTime = $record->end_time instanceof \Illuminate\Support\Carbon
                            ? $record->end_time
                            : \Illuminate\Support\Carbon::parse($record->end_time);

                        if ($endTime->isToday()) {
                            return Color::Red;
                        }
                        if ($endTime->isBetween(now(), now()->addDays(3), true)) {
                            return Color::Yellow;
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(\App\Filament\Components\Filters\BaseFilterGroup::show())
            ->actions(
                auth()->user()->isStudent()
                    ? [
                        Actions\ViewAction::make(),
                        Actions\Action::make('submit-document')
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
                                $mediaId = $data['media_id'];
                                $description = $data['description'] ?? null;
                                $originalName = \Awcodes\Curator\Models\Media::find($mediaId)?->name ?? null;

                                try {
                                    $document = Document::create([
                                        'exam_id' => $record->id,
                                        'class_id' => $record->class->id,
                                        'subject_id' => $record->class->subject->id,
                                        'media_id' => $mediaId,
                                        'status' => DocumentStatus::PENDING,
                                        'original_name' => $originalName,
                                        'description' => $description,
                                    ]);

                                    Notification::make()
                                        ->title('Document submitted successfully.')
                                        ->success()
                                        ->send();
                                } catch (\Throwable $th) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Error')
                                        ->body($th->getMessage())
                                        ->send();
                                }

                                ProcessSubmitDocument::dispatch($mediaId, [
                                    'document_id' => $document->id,
                                    'class_id' => $record->class->id,
                                    'subject_id' => $record->class->subject->id,
                                ]);
                            })
                            ->visible(fn() => auth()->user()->isStudent())
                            ->modalHeading('Submit Document for Exam')
                            ->modalButton('Submit')
                    ]
                    : [
                        Actions\ViewAction::make(),
                        Actions\EditAction::make()->color(Color::Blue),
                        Actions\DeleteAction::make(),
                    ]
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->isTeacher()) {
            $classRoomIds = ClassRoom::query()->assignedCurrentUser()->get()->pluck('id')->toArray();
            return Exam::query()->whereIn('class_id', $classRoomIds);
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
