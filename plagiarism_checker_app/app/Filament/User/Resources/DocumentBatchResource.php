<?php

namespace App\Filament\User\Resources;

use App\Enums\DocumentStatus;
use App\Filament\Components\Forms\Selectors\SubjectSelector;
use App\Filament\User\Resources\DocumentBatchResource\Pages;
use App\Models\DocumentBatch;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Illuminate\Database\Eloquent\Builder;

class DocumentBatchResource extends Resource
{
    protected static ?string $model = DocumentBatch::class;

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Upload Documents';

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    public static function canAccess(): bool
    {
        return auth()->user()->isTeacher();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                CuratorPicker::make('media_id')
                    ->label('Document')
                    ->acceptedFileTypes([
                        'application/zip',                   // Standard ZIP files
                        'application/x-zip-compressed',      // Windows compressed ZIP files
                        'multipart/x-zip',                   // Older ZIP format
                        'application/x-rar-compressed',      // RAR files
                        'application/vnd.rar',               // Another RAR MIME type
                        'application/x-rar',                 // RAR format
                    ])
                    ->maxSize(30720000)
                    ->required(),

                CuratorPicker::make('media_path_id')
                    ->label('Path File (Excel)')
                    ->helperText('Add a catalog file (Excel) that includes name, subject, and metadata for each document. You can empty this field.')
                    ->acceptedFileTypes([
                        'application/vnd.ms-excel', // For .xls
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // For .xlsx
                    ])
                    ->maxSize(100),
                
                SubjectSelector::show()->required(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media')
                    ->label('Archive File')
                    ->size(40),
                    
                CuratorColumn::make('mediaPath')
                    ->label('Excel File')
                    ->size(40),
                    
                TextColumn::make('media.size')
                    ->label('Archive Size')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024 / 1024, 2) . ' MB'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (DocumentStatus $state): string => $state->getColor())
                    ->formatStateUsing(fn (DocumentStatus $state): string => $state->getLabel()),
                    
                TextColumn::make('documents_count')
                    ->label('Total Files')
                    ->counts('documents'),
                    
                TextColumn::make('created_at')
                    ->label('Upload Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return DocumentBatch::query()->where('uploaded_by', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentBatches::route('/'),
            'create' => Pages\CreateDocumentBatch::route('/create'),
            'view' => Pages\ViewDocumentBatch::route('/{record}'),
        ];
    }
}
