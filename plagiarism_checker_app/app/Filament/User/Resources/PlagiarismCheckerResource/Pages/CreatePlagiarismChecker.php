<?php

namespace App\Filament\User\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\User\Pages\PlagiarismCheck;
use App\Filament\User\Pages\PlagiarismReport;
use App\Filament\User\Resources\PlagiarismCheckerResource;
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
            $data['give_me_file'] ?? false,
        ));
    }

    private function parsingDocument(?array $files = null, ?string $content = null, bool $giveMeFile): array
    {
        if ((! count($files)) && ! $content) {
            return [
                'preview_content' => null,
                'filename' => null,
                'file_path' => null,
                'give_me_file' => $giveMeFile,
            ];
        }

        if ($content) {
            return [
                'preview_content' => $content,
                'filename' => null,
                'file_path' => null,
                'give_me_file' => $giveMeFile,
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
                'give_me_file' => $giveMeFile,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error processing file: " . $e->getMessage());
        }
    }

    private function redirectToReportPage(array $previewData): void
    {
        if ($previewData['extension'] == 'pdf') {
            $this->redirect(PlagiarismReport::getUrl([
                'data' => base64_encode(json_encode($previewData))
            ]));    
        } else {
            $this->redirect(PlagiarismCheck::getUrl([
                'data' => base64_encode(json_encode($previewData))
            ]));
        }
    }
}
