<?php

namespace App\Services;

use App\Traits\PDFParser;
use App\Traits\TXTParser;
use App\Traits\WordParser;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TextRun;

class DocumentParser
{
    use WordParser;
    use PDFParser;
    use TXTParser;

    public function parse(string $filePath, string $extension, bool $forPreview = false)
    {
        return match ($extension) {
            'docx' => $this->parseDocx($filePath, $forPreview),
            'pdf' => $this->parsePdf($filePath, $forPreview),
            'txt' => $this->parseText($filePath, $forPreview),
            default => throw new \Exception("Unsupported file type"),
        };
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

    public function concatGroupedParagraphs(array $sections): array 
    {
        $result = [];
        
        foreach ($sections as $section) {
            foreach ($section as $groupKey => $elements) {
                $text = '';
                
                foreach ($elements as $element) {
                    $text .= $this->extractElementText($element);
                }
                
                $text = trim($text);
                $textLength = mb_strlen($text, 'UTF-8');
                if (! empty($text) && $textLength >= config('document-parse.min_paragraph_length')) {
                    $result[$groupKey] = $text;
                }
            }
        }
        
        return $this->removeSoftParagraph($result);
    }

    private function removeSoftParagraph(array $paragraphs): array
    {
        return array_filter($paragraphs, fn($text) => strlen($text) >= config('document-parse.min_paragraph_length'));
    }

    private function extractElementText(array $element): string 
    {
        return match($element['type'] ?? null) {
            'paragraph', 'heading' => $this->extractContentText($element['content']),
            'table' => $this->extractTableText($element['rows']),
            'list-item' => $this->extractContentText($element['content']),
            'text-break' => "\n",
            default => ''
        };
    }

    private function extractContentText(array $contents): string 
    {
        $text = '';
        foreach ($contents as $content) {
            $text .= $content['text'] ?? '';
            if (isset($content['link'])) {
                $text .= ' [' . $content['link'] . ']';
            }
        }
        return $text;
    }

    private function extractTableText(array $rows): string 
    {
        $text = '';
        foreach ($rows as $row) {
            $rowText = [];
            foreach ($row['cells'] as $cell) {
                $cellText = '';
                foreach ($cell['content'] as $element) {
                    $cellText .= $this->extractElementText($element);
                }
                $rowText[] = trim($cellText);
            }
            $text .= implode(" | ", array_filter($rowText)) . "\n";
        }
        return $text;
    }
}
