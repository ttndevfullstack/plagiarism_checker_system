<?php

namespace App\Filament\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\Resources\PlagiarismCheckerResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Get;

class CreatePlagiarismChecker extends CreateRecord
{
    protected static string $resource = PlagiarismCheckerResource::class;

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label(__('Check Plagiarism'))
                ->action(function (array $data) {
                    if (!empty($data['document'])) {
                        $this->checkPlagiarism($data['document']);
                    } elseif (!empty($data['content'])) {
                        $this->checkPlagiarism(null, $data['content']);
                    }
                })
                ->keyBindings(['mod+s']),
            $this->getCancelFormAction(),
        ];
    }

    private function checkPlagiarism(?string $file = null, ?string $content = null): void
    {
        // If is content write to txt file
        // Call to plagiarism checker API
        // Save the response to the database
        // Redirect to the results page
    }
}
