<?php

namespace App\Filament\User\Resources;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Filament\User\Resources\DocumentResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Document Management';

    protected static ?string $navigationIcon = 'heroicon-s-document';

    public static function canAccess(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->relationship('class', 'name'),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
                Forms\Components\Textarea::make('description'),
                CuratorPicker::make('media_id')
                    ->label('Document')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(30720000)
                    ->preserveFilenames()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media')
                    ->size(40),
                TextColumn::make('original_name')->searchable(),
                TextColumn::make('subject.name')->searchable(),
                TextColumn::make('class.name')->searchable(),
                TextColumn::make('description'),
                TextColumn::make('media.size')
                    ->formatStateUsing(fn($state) => number_format($state / 1024 / 1024, 2) . ' MB'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(DocumentStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn(DocumentStatus $state): string => $state->getLabel()),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('Subject'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
