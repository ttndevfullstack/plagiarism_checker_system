<?php

namespace App\Traits;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

trait RawParser
{
    public function parseRaw(string $filePath, string $extension)
    {
        return match ($extension) {
            'docx' => $this->parseRawDocx($filePath),
            'pdf' => $this->parseRawPdf($filePath),
            default => throw new \Exception("Unsupported file type"),
        };
    }

    public function parseRawDocx(string $filePath): \PhpOffice\PhpWord\PhpWord
    {
        return IOFactory::load($filePath);
    }

    public function outputHighlightTextDocxFile(\PhpOffice\PhpWord\PhpWord $phpWord, string $outputFileName): string
    {
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            // Create or get existing font style
                            $fontStyle = $textElement->getFontStyle();
                            if ($fontStyle === null) {
                                $fontStyle = new \PhpOffice\PhpWord\Style\Font();
                            }
                            
                            // Set highlight color
                            $fontStyle->setBgColor('yellow');
                            $textElement->setFontStyle($fontStyle);
                        }
                    }
                }
            }
        }

        // Ensure the output directory exists
        $publicPath = public_path('downloads');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Generate safe output filename
        $safeFilename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $outputFileName);
        $outputPath = $publicPath . '/' . $safeFilename;

        // Save the document
        $phpWord->save($outputPath);

        // Return the public URL for download
        return asset('downloads/' . $safeFilename);
    }

    public function parseRawPdf(string $filePath)
    {
        // TODO:
    }
}