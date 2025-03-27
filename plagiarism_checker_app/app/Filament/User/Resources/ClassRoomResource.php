<?php

namespace App\Filament\User\Resources;

use App\Filament\Components\Forms\Selectors\TeacherSelector;
use App\Filament\Components\Forms\Selectors\SubjectSelector;
use App\Filament\User\Resources\ClassRoomResource\Pages;
use App\Models\ClassRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Assigned Class Room';

    protected static ?string $navigationIcon = 'heroicon-s-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_number')
                    ->required()
                    ->disabled(),
                
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
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(\App\Filament\Components\Filters\BaseFilterGroup::show())
            ->actions(\App\Filament\Components\Actions\BaseActionGroup::show())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->assignedCurrentUser();
    }

    /**
     * @return array<string, \Filament\Resources\Pages\Page> 
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassRooms::route('/'),
            'create' => Pages\CreateClassRoom::route('/create'),
            'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }
}
