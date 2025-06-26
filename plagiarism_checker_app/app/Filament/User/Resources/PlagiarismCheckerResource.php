<?php

namespace App\Filament\User\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use App\Models\PlagiarismHistory;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use App\Filament\User\Resources\PlagiarismCheckerResource\Pages;
use Filament\Forms\Components\RichEditor;

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
            Tables\Columns\TextColumn::make('created_at')
                ->label('Check Date')
                ->dateTime(),
            Tables\Columns\TextColumn::make('similarity_score')
                ->label('Similarity Score')
                ->formatStateUsing(fn($state) => number_format($state, 2) . '%'),
            Tables\Columns\TextColumn::make('confidence_score')
                ->label('Confidence')
                ->formatStateUsing(fn($state) => str_repeat('⭐️', $state)),
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