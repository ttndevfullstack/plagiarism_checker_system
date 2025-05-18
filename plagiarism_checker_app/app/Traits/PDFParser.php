<?php

namespace App\Traits;

use setasign\Fpdi\Fpdi;

trait PDFParser
{
    public Fpdi $pdfParser;

    public string $outputFilePath;

    protected function initializePdfParser(): void
    {
        $this->pdfParser = new Fpdi();
        $this->pdfParser->SetAutoPageBreak(true, 15);
    }

    /**
     * Parse PDF file
     */
    public function parsePdf(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("PDF file not found at path: {$filePath}");
        }

        $this->initializePdfParser();
        $this->pdfParser->setSourceFile($filePath);
    }

    protected function highlightTextAreas(int $pageNo, array $size): void
    {
        // Example coordinates - you'll need to adjust these based on your PDF structure
        $textAreas = [
            ['x' => 20, 'y' => 50, 'w' => $size['width'] - 40, 'h' => $size['height'] - 70]
        ];
        
        foreach ($textAreas as $area) {
            $this->pdfParser->SetFillColor(255, 255, 0); // Yellow
            $this->pdfParser->Rect(
                $area['x'], 
                $area['y'], 
                $area['w'], 
                $area['h'], 
                'F' // Fill mode
            );
        }
    }

    /**
     * Clean up generated files
     */
    public function cleanup(): void
    {
        if (isset($this->outputFilePath)) {
            @unlink($this->outputFilePath);
        }
    }
}