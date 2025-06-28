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
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => StudentResource::getUrl('view', ['record' => $record->getKey()])),
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => StudentResource::getUrl('edit', ['record' => $record->getKey()])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
