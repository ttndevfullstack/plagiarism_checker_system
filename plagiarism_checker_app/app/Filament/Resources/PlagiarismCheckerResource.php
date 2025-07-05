<?php

namespace App\Filament\Resources;

use App\Models\PlagiarismHistory;
use App\Filament\Resources\PlagiarismCheckerResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;

class PlagiarismCheckerResource extends Resource
{
    protected static ?string $model = PlagiarismHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $navigationLabel = 'Plagiarism History';

    protected static ?string $modelLabel = 'Plagiarism Check';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make()
                ->heading('Check Plagiarism With Proofly')
                ->description('Upload a file or paste text to check for plagiarism')
                ->collapsible()
                ->maxWidth('full')
                ->schema([
                    Tabs::make('Checker')
                        ->persistTab(false)
                        ->tabs([
                            Tabs\Tab::make('Paste Text')
                                ->id('paste-text')
                                ->schema([
                                    RichEditor::make('content')
                                        ->label('Text Content')
                                        ->required()
                                        ->minLength(50)
                                        ->maxLength(50000)
                                        ->helperText('Paste your text here (50-50,000 characters)')
                                        ->disableGrammarly()
                                        ->toolbarButtons([
                                            'attachFiles',
                                            'blockquote',
                                            'bold',
                                            'bulletList',
                                            'codeBlock',
                                            'h2',
                                            'h3',
                                            'italic',
                                            'link',
                                            'orderedList',
                                            'redo',
                                            'strike',
                                            'underline',
                                            'undo',
                                        ])
                                        ->columnSpanFull(),
                                ]),

                            Tabs\Tab::make('Upload File')
                                ->id('upload-file')
                                ->schema([
                                    Forms\Components\FileUpload::make('document')
                                        ->label('Upload Document')
                                        ->acceptedFileTypes([
                                            'application/pdf',
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                            'text/plain'
                                        ])
                                        ->maxSize(10240)
                                        ->helperText('Supported formats: PDF, DOCX, TXT (Max 10MB)')
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(12),
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
            Tables\Columns\BadgeColumn::make('originality_score')
                ->label('Originality Score'),
            Tables\Columns\BadgeColumn::make('similarity_score')
                ->label('Similarity Score')
                ->color(fn ($record) => match (true) {
                    $record->similarity_score >= 70 => 'danger',
                    $record->similarity_score >= 40 => 'warning',
                    default => 'success',
                }),
            Tables\Columns\TextColumn::make('source_matched')
                ->label('Source Matched'),
            Tables\Columns\TextColumn::make('words_analyzed')
                ->label('Words Analyzed'),
            Tables\Columns\TextColumn::make('uploader.full_name')
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
