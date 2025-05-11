<?php

namespace App\Filament\User\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\User\Resources\PlagiarismCheckerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlagiarismChecker extends ListRecords
{
    protected static string $resource = PlagiarismCheckerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('Check Plagiarism')),
        ];
    }
}
