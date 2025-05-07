<?php
namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use Smalot\PdfParser\Parser as PdfParser;

class DocumentParser
{
    public function parse(string $filePath, string $extension, bool $forPreview = false)
    {
        return match ($extension) {
            'docx' => $this->parseDocx($filePath, $forPreview),
            'pdf' => $this->parsePdf($filePath),
            'txt' => $this->parseText($filePath),
            default => throw new \Exception("Unsupported file type"),
        };
    }

    protected function parseDocx($filePath, $forPreview = false)
    {
        $phpWord = IOFactory::load($filePath);
        
        if ($forPreview) {
            return $this->parseForPreview($phpWord);
        }
        
        return $this->parseForPlainText($phpWord);
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

    protected function parseForPreview(\PhpOffice\PhpWord\PhpWord $phpWord)
    {
        $content = [];
        
        foreach ($phpWord->getSections() as $section) {
            $sectionContent = $this->parseSection($section);
            if (!empty($sectionContent)) {
                $content[] = $sectionContent;
            }
        }
        
        return $content;
    }

    protected function parseSection(Section $section)
    {
        $elements = [];
        
        foreach ($section->getElements() as $element) {
            if ($element instanceof TextRun) {
                $elements[] = $this->parseTextRun($element);
            } elseif ($element instanceof Text) {
                $elements[] = $this->parseTextElement($element);
            } elseif ($element instanceof Table) {
                $elements[] = $this->parseTable($element);
            }
            // Add other element types as needed
        }
        
        return $elements;
    }

    protected function parseTextRun(TextRun $textRun)
    {
        $texts = [];
        
        foreach ($textRun->getElements() as $element) {
            if ($element instanceof Text) {
                $texts[] = $this->parseTextElement($element);
            }
        }
        
        return [
            'type' => 'paragraph',
            'content' => $texts
        ];
    }

    protected function parseTextElement(Text $text)
    {
        $font = $text->getFontStyle();
        $fontData = null;
        
        if ($font) {
            $fontData = [
                'bold' => $font->isBold(),
                'italic' => $font->isItalic(),
                'underline' => $font->getUnderline(),
                'color' => $font->getColor(),
                'size' => $font->getSize(),
                'name' => $font->getName(),
            ];
        }
        
        return [
            'text' => $text->getText(),
            'font' => $fontData
        ];
    }

    protected function parseTable(Table $table)
    {
        $rows = [];
        
        foreach ($table->getRows() as $row) {
            $cells = [];
            
            foreach ($row->getCells() as $cell) {
                $cellContent = [];
                
                foreach ($cell->getElements() as $element) {
                    if ($element instanceof TextRun) {
                        $cellContent[] = $this->parseTextRun($element);
                    } elseif ($element instanceof Text) {
                        $cellContent[] = $this->parseTextElement($element);
                    }
                }
                
                $cells[] = [
                    'content' => $cellContent
                ];
            }
            
            $rows[] = [
                'cells' => $cells
            ];
        }
        
        return [
            'type' => 'table',
            'rows' => $rows
        ];
    }

    protected function extractText($element)
    {
        if ($element instanceof TextRun) {
            $text = '';
            foreach ($element->getElements() as $childElement) {
                if ($childElement instanceof Text) {
                    $text .= $childElement->getText();
                }
            }
            return $text;
        } elseif ($element instanceof Text) {
            return $element->getText();
        }
        
        return '';
    }

    protected function parsePdf($filePath)
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    protected function parseText($filePath)
    {
        return file_get_contents($filePath);
    }
}