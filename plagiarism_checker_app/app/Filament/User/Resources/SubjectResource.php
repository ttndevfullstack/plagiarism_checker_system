<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Class Management';

    protected static ?string $navigationLabel = 'Subject Management';

    protected static ?string $navigationIcon = 'heroicon-s-book-open';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $code = collect(explode(' ', $state))
                                ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
                                ->join('');
                            $set('code', $code);
                        }
                    }),
                    
                Forms\Components\TextInput::make('code')
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\Textarea::make('description')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(\App\Filament\Components\Filters\BaseFilterGroup::show())
            ->actions([\Filament\Tables\Actions\ViewAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'view' => Pages\ViewSubject::route('/{record}'),
        ];
    }
}
