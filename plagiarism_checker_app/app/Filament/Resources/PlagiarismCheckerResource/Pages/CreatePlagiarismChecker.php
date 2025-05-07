<?php

namespace App\Filament\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\Pages\PlagiarismCheck;
use App\Filament\Resources\PlagiarismCheckerResource;
use App\Services\DocumentParser;
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
            ];
        }

        if ($content) {
            return [
                'preview_content' => $content,
                'filename' => null,
            ];
        }

        try {
            $file = reset($files);
            $filePath = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            $filename = $file->getClientOriginalName();

            $parser = new DocumentParser();
            $previewContent = $parser->parse($filePath, $extension, true);

            return [
                'preview_content' => $previewContent,
                'filename' => $filename,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error processing file: " . $e->getMessage());
        }
    }

    private function rawParser(?array $files = null, ?string $content = null): void
    {
        $previewData = [];

        if ($files && count($files)) {
            $file = reset($files);

            try {
                $filePath = $file->getRealPath();
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = $file->getClientOriginalName();

                $documentParser = new DocumentParser();
                $previewData['content'] = $documentParser->parse($filePath, $extension);
                $previewData['filename'] = $filename;
            } catch (\Exception $e) {
                $previewData['content'] = "Error processing file: " . $e->getMessage();
            }
        } else {
            $previewData['content'] = $content ?? "No content provided";
        }
    }

    private function redirectToReportPage(array $previewData): void
    {
        $this->redirect(PlagiarismCheck::getUrl([
            'data' => base64_encode(json_encode($previewData))
        ]));
    }
}
