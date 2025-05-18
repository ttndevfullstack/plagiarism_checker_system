<?php

namespace App\Traits;

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\PhpWord;

trait PDFParser
{
    public function parsePdf($filePath, $forPreview = false)
    {
        $filePath = $this->convertPdfToDocx($filePath);

        return $this->parseDocx($filePath, $forPreview);
    }

    protected function convertPdfToDocx(string $pdfPath): string
    {
        // Validate input file exists
        if (!file_exists($pdfPath)) {
            throw new \RuntimeException("PDF file not found at path: {$pdfPath}");
        }

        // Generate output path by changing extension to .docx
        $outputPath = pathinfo($pdfPath, PATHINFO_DIRNAME) . '/'
            . pathinfo($pdfPath, PATHINFO_FILENAME) . '.docx';

        // Parse PDF content
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($pdfPath);
        $text = $pdf->getText();

        // Create Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText($text);

        // Save Word document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($outputPath);

        // Verify output file was created
        if (!file_exists($outputPath)) {
            throw new \RuntimeException("Failed to create DOCX file at: {$outputPath}");
        }

        return $outputPath;
    }
}