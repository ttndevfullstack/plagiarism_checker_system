<?php

namespace App\Services;

use App\Traits\WordParser;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;

class DocumentParser
{
    use WordParser;

    public function parse(string $filePath, string $extension, bool $forPreview = false)
    {
        return match ($extension) {
            'docx' => $this->parseDocx($filePath, $forPreview),
            'txt' => $this->parseText($filePath, $forPreview),
            default => throw new \Exception("Unsupported file type"),
        };
    }

    public function outputDocument(PhpWord $parser, string $outputFileName): string
    {
        // Ensure the output directory exists
        $publicPath = public_path('downloads');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Generate safe output filename
        $safeFilename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $outputFileName);
        $outputPath = $publicPath . '/' . $safeFilename;

        // Save the document
        $parser->save($outputPath);

        // Return the public URL for download
        return asset('downloads/' . $safeFilename);
    }

    public function getCleanedWordText(): array
    {
        return $this->extractWordTextByParagraph();
    }

    public function getCleanedPDFText(): array
    {
        return $this->extractPDFTextByParagraph();
    }

    protected function parseForPlainText(\PhpOffice\PhpWord\PhpWord $phpWord)
    {
        $text = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text[] = $this->extractText($element);
            }
        }

        return implode("\n", array_filter($text));
    }

    protected function extractText($element)
    {
        if ($element instanceof Title) {
            if ($element->getText() instanceof TextRun) {
                $text = '';
                foreach ($element->getText()->getElements() as $childElement) {
                    if ($childElement instanceof Text) {
                        $text .= $childElement->getText();
                    }
                }
                return $text;
            }
            return $element->getText();
        } elseif ($element instanceof TextRun) {
            $text = '';
            foreach ($element->getElements() as $childElement) {
                if ($childElement instanceof Text) {
                    $text .= $childElement->getText();
                }
            }
            return $text;
        } elseif ($element instanceof Text) {
            return $element->getText();
        } elseif ($element instanceof Title) {
            return $element->getText();
        }

        return '';
    }

    protected function outputHighlightedPdf(string $outputPath): string
    {
        // Copy the highlighted PDF from Flask response
        if (isset($this->highlightedPdfPath)) {
            copy($this->highlightedPdfPath, $outputPath);
        }
        
        return asset('downloads/' . basename($outputPath));
    }
}
