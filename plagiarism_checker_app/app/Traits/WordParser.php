<?php

namespace App\Traits;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\AbstractElement;
use setasign\Fpdi\Fpdi;

trait WordParser
{
    public PhpWord $phpWord;

    public array $wordPreviewContent = [];

    public function parseDocx($filePath, $forPreview = false)
    {
        $this->phpWord = IOFactory::load($filePath);

        if ($forPreview) {
            return $this->parseForPreview($this->phpWord);
        }

        return $this->parseForPlainText($this->phpWord);
    }

    public function parseForPreview(PhpWord $phpWord)
    {
        foreach ($phpWord->getSections() as $section) {
            $sectionContent = $this->parseSection($section);
            if (!empty($sectionContent)) {
                $this->wordPreviewContent[] = $sectionContent;
            }
        }
    }

    public function parseSection(Section $section)
    {
        $elements = [];
        $groupedElements = $this->groupParagraphElements($section->getElements());
        
        foreach ($groupedElements as $index => $groupedElement) {
            $parsedElements = [];

            foreach ($groupedElement as $element) {
                $element->setRelationId("text-{$index}");
                $parsedElement = $this->parseElement($element);

                if ($parsedElement !== null) {
                    $parsedElements[] = $parsedElement;
                }
            }
            $elements["text-{$index}"] = $parsedElements;
        }

        return $elements;
    }

    public function groupParagraphElements(array $elements): array
    {
        $groups = [];
        $currentGroup = [];

        foreach ($elements as $index => $element) {
            $currentGroup[] = $element;
            
            if ($index + 1 == count($elements)) {
                if (! empty($currentGroup)) {
                    $groups[] = $currentGroup;
                    $currentGroup = [];
                    break;
                }
            }

            $nextElement = $elements[$index + 1];

            // For title
            if ($element instanceof Title) {
                $groups[] = $currentGroup;
                $currentGroup = [];
                continue;
            }

            // For empty line and empty page
            if ($element instanceof TextBreak || $element instanceof PageBreak) {
                $groups[] = $currentGroup;
                $currentGroup = [];
                continue;
            }

            // For table
            if ($element instanceof Table) {
                $groups[] = $currentGroup;
                $currentGroup = [];
                continue;
            }

            // For table
            if ($element instanceof ListItemRun) {
                // The same type
                if (get_class($element) == get_class($nextElement)) {
                    continue;
                } else {
                    $groups[] = $currentGroup;
                    $currentGroup = [];
                    continue;
                }
            }

            $textLength = mb_strlen($element->getText(), 'UTF-8');
            if ($textLength >= config('document-parse.min_paragraph_length')) {
                $groups[] = $currentGroup;
                $currentGroup = [];
                continue;
            } else {
                // The same type
                if (get_class($element) == get_class($nextElement)) {
                    continue;
                } else {
                    $groups[] = $currentGroup;
                    $currentGroup = [];
                    continue;
                }
            }
        }

        // Push remaining group
        if (! empty($currentGroup)) {
            $groups[] = $currentGroup;
        }

        return $groups;
    }

    public function parseElement(AbstractElement $element)
    {
        if ($element instanceof TextRun) {
            return $this->parseTextRun($element);
        } elseif ($element instanceof Text) {
            return $this->parseTextElement($element);
        } elseif ($element instanceof Table) {
            return $this->parseTable($element);
        } elseif ($element instanceof Title) {
            return $this->parseTitle($element);
        } elseif ($element instanceof Link) {
            return $this->parseLinkElement($element);
        } elseif ($element instanceof TextBreak) {
            return $this->parseTextBreak();
        } elseif ($element instanceof ListItemRun) {
            return $this->parseListItemRun($element);
        }

        return null;
    }

    public function parseTextRun(TextRun $textRun)
    {
        $content = [];
        $alignment = $textRun->getParagraphStyle() ? $textRun->getParagraphStyle()->getAlignment() : 'left';

        foreach ($textRun->getElements() as $element) {
            if ($element instanceof Text) {
                $parsed = $this->parseTextElement($element);
                $parsed['alignment'] = $alignment;
                $content[] = $parsed;
            } elseif ($element instanceof Link) {
                $parsed = $this->parseLinkElement($element);
                $parsed['alignment'] = $alignment;
                $content[] = $parsed;
            }
        }

        // Group consecutive text elements with the same styling
        $groupedContent = [];
        $currentGroup = null;

        foreach ($content as $item) {
            if ($currentGroup === null) {
                $currentGroup = $item;
            } elseif ($this->isSameStyle($currentGroup, $item)) {
                $currentGroup['text'] .= $item['text'];
                if (isset($item['link'])) {
                    $currentGroup['link'] = $item['link'];
                }
            } else {
                $groupedContent[] = $currentGroup;
                $currentGroup = $item;
            }
        }

        if ($currentGroup !== null) {
            $groupedContent[] = $currentGroup;
        }

        return [
            'type' => 'paragraph',
            'alignment' => $alignment,
            'content' => $groupedContent
        ];
    }

    public function parseTable(Table $table)
    {
        $rows = [];

        foreach ($table->getRows() as $row) {
            $cells = [];

            foreach ($row->getCells() as $cell) {
                $cellContent = [];

                foreach ($cell->getElements() as $element) {
                    $parsedElement = $this->parseElement($element);
                    if ($parsedElement !== null) {
                        $cellContent[] = $parsedElement;
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

    protected function parseTitle(Title $title)
    {
        $content = [];
        $alignment = 'left';
        $text = '';
        $style = $title->getStyle();

        // Handle text content which might be TextRun or direct text
        if ($title->getText() instanceof TextRun) {
            $textRun = $title->getText();
            foreach ($textRun->getElements() as $element) {
                if ($element instanceof Text) {
                    $text .= $element->getText();
                }
            }
        } else {
            $text = $title->getText();
        }

        if ($style == 'Heading1') {
            $alignment = 'center';
        }

        if (method_exists($title, 'getParent')) {
            $parent = $title->getParent();
            if ($parent instanceof Section && method_exists($parent, 'getStyle')) {
                $sectionStyle = $parent->getStyle();
                if ($sectionStyle && method_exists($sectionStyle, 'getAlignment')) {
                    $alignment = $sectionStyle->getAlignment() ?: 'center';
                }
            }
        }

        $content[] = [
            'text' => $text,
            'font' => [
                'bold' => true,
                'italic' => false,
                'underline' => false,
                'color' => '000000',
                'size' => 24 - ($title->getDepth() * 2),
                'name' => 'Times New Roman'
            ],
            'alignment' => $alignment
        ];

        return [
            'type' => 'heading',
            'level' => $title->getDepth(),
            'alignment' => $alignment,
            'content' => $content
        ];
    }

    public function parseTextElement(Text $text)
    {
        $paragraphStyle = $text->getParagraphStyle();
        
        return [
            'text' => $text->getText(),
            'font' => $this->getFontData($text),
            'alignment' => $paragraphStyle ? $paragraphStyle->getAlignment() : 'left'
        ];
    }

    public function parseLinkElement(Link $link)
    {
        return [
            'text' => $link->getText(),
            'font' => $this->getFontData($link),
            'link' => $link->getSource()
        ];
    }

    protected function parseTextBreak()
    {
        return [
            'type' => 'text-break',
            'content' => []
        ];
    }

    protected function parseListItemRun(ListItemRun $listItem)
    {
        $content = [];
        $listStyle = $listItem->getStyle();
        $alignment = $listItem->getParagraphStyle() ? $listItem->getParagraphStyle()->getAlignment() : 'left';

        foreach ($listItem->getElements() as $element) {
            if ($element instanceof Text) {
                $parsed = $this->parseTextElement($element);
                $parsed['alignment'] = $alignment;
                $content[] = $parsed;
            } elseif ($element instanceof Link) {
                $parsed = $this->parseLinkElement($element);
                $parsed['alignment'] = $alignment;
                $content[] = $parsed;
            }
        }

        return [
            'type' => 'list-item',
            'level' => $listItem->getDepth(),
            'listType' => $listStyle->getListType(),
            'alignment' => $alignment,
            'content' => $content
        ];
    }

    public function getFontData($element)
    {
        $font = $element->getFontStyle();

        if (!$font) {
            return null;
        }

        return [
            'bold' => $font->isBold() ?: $font->getStyleName() === 'Strong',
            'italic' => $font->isItalic(),
            'underline' => $font->getUnderline() !== 'none',
            'color' => $font->getColor() ?: '000000',
            'size' => $font->getSize() ?: 12,
            'name' => $font->getName() ?: 'Times New Roman',
            'alignment' => $element->getParagraphStyle() ? $element->getParagraphStyle()->getAlignment() : 'left'
        ];
    }

    public function isSameStyle(array $style1, array $style2)
    {
        if (isset($style1['link']) !== isset($style2['link'])) {
            return false;
        }

        $font1 = $style1['font'] ?? null;
        $font2 = $style2['font'] ?? null;

        if ($font1 === null || $font2 === null) {
            return $font1 === $font2;
        }

        return $font1['bold'] === $font2['bold'] &&
            $font1['italic'] === $font2['italic'] &&
            $font1['underline'] === $font2['underline'] &&
            $font1['color'] === $font2['color'] &&
            $font1['size'] === $font2['size'] &&
            $font1['name'] === $font2['name'];
    }

    public function extractWordTextByParagraph(): array 
    {
        $result = [];
        
        foreach ($this->wordPreviewContent as $section) {
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