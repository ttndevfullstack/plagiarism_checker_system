<?php

namespace App\Filament\Resources\ClassRoomResource\Widgets;

use App\Filament\Components\Filters\BaseFilterGroup;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Document;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class SubmittedDocumentListWidget extends TableWidget
{
    public Exam $record;

    public ClassRoom $classroom;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->whereHas('enrollments', function ($query) {
                        $query->where('class_id', $this->classroom->id);
                    })
                    ->with('user')
            )
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
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Submitted Document'))
                    ->getStateUsing(function ($record) {
                        $document = Document::where('uploaded_by', $record->user_id)
                            ->where('class_id', $this->classroom->id)
                            ->where('exam_id', $this->record->id)
                            ->first();

                        if (! $document) {
                            return 'Not Submitted';
                        }

                        if ($document->created_at->gt($record->end_time)) {
                            return 'Submitted Late';
                        }

                        return 'Submitted';
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'Not Submitted' => 'gray',
                            'Submitted Late' => 'danger',
                            'Submitted' => 'success',
                            default => 'secondary',
                        };
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(BaseFilterGroup::show());
    }
}
