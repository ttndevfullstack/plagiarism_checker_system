<?php

namespace App\Filament\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\Pages\Loading;
use App\Filament\Resources\PlagiarismCheckerResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

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
                ->action(function () {
                    $this->parseDocumentAndRedirect($this->form->getRawState());
                })
                ->keyBindings(['mod+s']),
            $this->getCancelFormAction(),
        ];
    }

    private function parseDocumentAndRedirect(array $data): void
    {
        $this->redirectToReportPage($this->parsingDocument(
            $data['document'] ?? null,
            $data['content'] ?? null,
        ));
    }

    private function parsingDocument(?array $files = null, ?string $content = null): array
    {
        if ((! count($files)) && ! $content) {
            return [
                'preview_content' => null,
                'filename' => null,
                'file_path' => null,
            ];
        }

        if ($content) {
            return [
                'preview_content' => $content,
                'filename' => null,
                'file_path' => null,
            ];
        }

        try {
            $file = reset($files);
            $filePath = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            $filename = $file->getClientOriginalName();

            return [
                'file_path' => $filePath,
                'filename' => $filename,
                'extension' => $extension,
                'preview_content' => null,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error processing file: " . $e->getMessage());
        }
    }

    private function redirectToReportPage(array $previewData): void
    {
        $this->redirect(Loading::getUrl([
            'data' => base64_encode(json_encode($previewData))
        ]));
    }
}
