<?php

namespace App\Filament\Resources;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Filament\Resources\DocumentBatchResource\Pages;
use App\Models\DocumentBatch;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ProgressColumn;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;

class DocumentBatchResource extends Resource
{
    protected static ?string $model = DocumentBatch::class;

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationGroup = 'Data Management';

    protected static ?string $navigationLabel = 'Upload Documents';

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                CuratorPicker::make('media_id')
                    ->label('Document')
                    ->acceptedFileTypes([
                        'application/zip',
                        'application/x-rar-compressed',
                        'application/vnd.rar',
                        'application/x-rar',    
                    ])
                    ->maxSize(30720000)
                    ->required(),
                CuratorPicker::make('path_file')
                    ->label('Path File (Excel)')
                    ->acceptedFileTypes([
                        'application/vnd.ms-excel', // For .xls
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // For .xlsx
                    ])
                    ->maxSize(100)
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                CuratorColumn::make('name')
                    ->size(40),
                CuratorColumn::make('metadata')
                    ->size(40),
                CuratorColumn::make('media.size')
                    ->size(40),
                CuratorColumn::make('media.ext')
                    ->size(40),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc');
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
