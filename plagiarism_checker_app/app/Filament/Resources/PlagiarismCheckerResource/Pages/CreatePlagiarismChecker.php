<?php

namespace App\Filament\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\Pages\PlagiarismCheck;
use App\Filament\Resources\PlagiarismCheckerResource;
use App\Services\DocumentParser;
use App\Services\TextSlicer;
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

            $rawContent = (new DocumentParser())->parse($filePath, $extension);
            $previewContent = (new DocumentParser())->parse($filePath, $extension, true);
            // $slicedContent = (new TextSlicer)->slice($rawContent);

            $headings = [];
            foreach ($previewContent[0] as $item) {
                $isHeading = isset($item['type']) && $item['type'] === 'heading';
                $isTitle = isset($item['type']) && $item['type'] === 'paragraph' && $item['content'][0]['font']['bold'] === true && strlen($item['content'][0]['text'] ?? []) <= 180;
                
                if ($isHeading || $isTitle) {
                    if (isset($item['content'][0]['text'])) {
                        $headings[] = $item['content'][0]['text'];
                    }
                }
            }
            // dd($previewContent[0][33]);
            dd($headings);

            return [
                'filename' => $filename,
                'rawContent' => $rawContent,
                'preview_content' => $previewContent,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error processing file: " . $e->getMessage());
        }
    }

    private function redirectToReportPage(array $previewData): void
    {
        $this->redirect(PlagiarismCheck::getUrl([
            'data' => base64_encode(json_encode($previewData))
        ]));
    }
}
