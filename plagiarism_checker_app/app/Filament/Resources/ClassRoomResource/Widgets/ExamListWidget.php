<?php

namespace App\Filament\Resources\ClassRoomResource\Widgets;

use Filament\Widgets\TableWidget;
use App\Enums\DocumentStatus;
use App\Filament\Resources\ExamResource;
use App\Jobs\ProcessSubmitDocument;
use App\Models\ClassRoom;
use App\Models\Document;
use App\Models\Exam;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class ExamListWidget extends TableWidget
{
    public ClassRoom $record;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Exam::query()->where('class_id', $this->record->id))
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
                        ->modalButton('Submit'),
                    Tables\Actions\ViewAction::make()
                        ->url(fn($record) => ExamResource::getUrl('view', ['record' => $record->getKey()])),
                    Tables\Actions\EditAction::make()
                        ->url(fn($record) => ExamResource::getUrl('edit', ['record' => $record->getKey()])),
                    Tables\Actions\DeleteAction::make(),
                ]
            );
    }
}
