<?php

namespace App\Filament\User\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use App\Models\PlagiarismHistory;
use Filament\Forms\Components\Section;
use App\Filament\User\Resources\PlagiarismCheckerResource\Pages;

class PlagiarismCheckerResource extends Resource
{
    protected static ?string $model = PlagiarismHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $navigationLabel = 'Plagiarism History';

    protected static ?string $modelLabel = 'Plagiarism Check';

    public static function canAccess(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make()
                ->heading('Plagiarism History Details')
                ->description('Edit all fields of the PlagiarismHistory record')
                ->collapsible()
                ->maxWidth('full')
                ->schema([
                    Forms\Components\Select::make('document_id')
                        ->label('Document')
                        ->relationship('document', 'original_name')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('class_id')
                        ->label('Class')
                        ->relationship('class', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('subject_id')
                        ->label('Subject')
                        ->relationship('subject', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('originality_score')
                        ->label('Originality Score')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('similarity_score')
                        ->label('Similarity Score')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('source_matched')
                        ->label('Source Matched')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('words_analyzed')
                        ->label('Words Analyzed')
                        ->numeric(),
                    Forms\Components\TextInput::make('encoded_file')
                        ->label('Encoded File')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('results')
                        ->label('Results (JSON)')
                        ->rows(4)
                        ->helperText('Paste JSON array/object here'),
                    Forms\Components\Select::make('uploaded_by')
                        ->label('Uploaded By')
                        ->relationship('document', 'uploaded_by')
                        ->searchable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\TextColumn::make('document.original_name')
                ->label('Document')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('class.name')
                ->label('Class')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('subject.name')
                ->label('Subject')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('originality_score')
                ->label('Originality Score'),
            Tables\Columns\TextColumn::make('similarity_score')
                ->label('Similarity Score'),
            Tables\Columns\TextColumn::make('source_matched')
                ->label('Source Matched'),
            Tables\Columns\TextColumn::make('words_analyzed')
                ->label('Words Analyzed'),
            Tables\Columns\TextColumn::make('uploaded_by')
                ->label('Uploaded By'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlagiarismChecker::route('/'),
            'create' => Pages\CreatePlagiarismChecker::route('/create'),
        ];
    }
}
