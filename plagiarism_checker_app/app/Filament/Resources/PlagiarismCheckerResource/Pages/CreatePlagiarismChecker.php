<?php

namespace App\Filament\Resources\PlagiarismCheckerResource\Pages;

use App\Filament\Pages\PlagiarismCheck;
use App\Filament\Resources\PlagiarismCheckerResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Smalot\PdfParser\Parser;

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
                    $data = $this->form->getRawState();

                    if (isset($data['document']) && !empty($data['document'])) {
                        $this->checkPlagiarism($data['document']);
                    } elseif (isset($data['content']) && !empty($data['content'])) {
                        $this->checkPlagiarism(null, $data['content']);
                    }
                })
                ->keyBindings(['mod+s']),
            $this->getCancelFormAction(),
        ];
    }

    private function checkPlagiarism(?array $files = null, ?string $content = null): void
    {
        $previewData = [];

        if ($files && count($files)) {
            $file = reset($files);

            try {
                $filePath = $file->getRealPath();
                $extension = $file->getClientOriginalExtension();
                $filename = $file->getClientOriginalName();

                switch (strtolower($extension)) {
                    case 'txt':
                        $previewData['content'] = file_get_contents($filePath);
                        break;

                    case 'docx':
                        $zip = new \ZipArchive();
                        if ($zip->open($filePath) === true) {
                            $content = $zip->getFromName('word/document.xml');
                            $zip->close();

                            if ($content) {
                                // Convert XML to text and clean up
                                $content = strip_tags($content);
                                $content = str_replace(['</w:p>', '</w:r>', '</w:t>'], "\n", $content);
                                $previewData['content'] = preg_replace('/[\r\n]{2,}/', "\n\n", trim($content));
                            } else {
                                $previewData['content'] = "Error: Could not extract DOCX content";
                            }
                        } else {
                            $previewData['content'] = "Error: Could not open DOCX file";
                        }
                        break;

                    case 'pdf':
                        try {
                            $parser = new Parser();
                            $pdf = $parser->parseFile($filePath);
                            $content = $pdf->getText();
                            $previewData['content'] = trim($content);
                        } catch (\Exception $e) {
                            $previewData['content'] = "Error extracting PDF content: " . $e->getMessage();
                        }
                        break;

                    default:
                        $previewData['content'] = "Unsupported file format: .$extension";
                }

                $previewData['filename'] = $filename;
            } catch (\Exception $e) {
                $previewData['content'] = "Error processing file: " . $e->getMessage();
            }
        } else {
            $previewData['content'] = $content ?? "No content provided";
        }

        $this->redirect(PlagiarismCheck::getUrl([
            'preview_content' => base64_encode(json_encode($previewData))
        ]));
    }
}
