<?php

namespace App\Filament\Resources\ClassRoomResource\Widgets;

use App\Models\Exam;
use App\Models\Document;
use App\Models\ClassRoom;
use App\Enums\DocumentStatus;
use App\Jobs\ProcessSubmitDocument;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;

class ExamListWidget extends TableWidget
{
    public ClassRoom $record;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $resourceName = auth()->user()->isAdmin()
            ? \App\Filament\Resources\ExamResource::class
            : \App\Filament\User\Resources\ExamResource::class;

        return $table
            ->query(Exam::query()->where('class_id', $this->record->id))
            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn () => null),
                Tables\Actions\Action::make('createExam')
                    ->label('Create Exam')
                    ->icon('heroicon-m-plus')
                    ->url(fn() => $resourceName::getUrl('create', ['class_id' => $this->record->id]))
                    ->visible(fn() => auth()->user()->isAdmin() || auth()->user()->isTeacher()),
            ])
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
                [
                    Tables\Actions\ViewAction::make()
                        ->url(fn($record) => $resourceName::getUrl('view', ['record' => $record->getKey()])),
                    Tables\Actions\EditAction::make()
                        ->url(fn($record) => $resourceName::getUrl('edit', ['record' => $record->getKey()])),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('submit-document')
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
                                'subject_id' => $record->class->subject->id,
                                'class_id' => $record->class->id,
                                'exam_id' => $record->id,
                            ]);
                        })
                        ->visible(fn() => auth()->user()->isStudent())
                        ->modalHeading('Submit Document for Exam')
                        ->modalButton('Submit'),
                ]
            );
    }
}
