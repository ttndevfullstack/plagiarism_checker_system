<?php

namespace App\Services;

use PhpOffice\PhpWord\Element\Title;

class TextSlicer
{
    public function slice(string $rawText): array
    {
        $chunks = [];

        // Normalize line breaks
        $text = preg_replace("/\r\n|\r/", "\n", $rawText);

        // Explode into lines
        $lines = explode("\n", $text);

        $currentChunk = '';
        $inBulletBlock = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Handle title-like lines
            if (preg_match('/^(Overview|Why you need Kubernetes|Key Features of Kubernetes|What Kubernetes is not|Historical context for Kubernetes|Traditional deployment era|Virtualized deployment era|Container deployment era|Advantages of Containers)/i', $trimmed)) {
                if (!empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                }
                $currentChunk = $trimmed . "\n";
                continue;
            }

            // Handle bullet points
            if (preg_match('/^[-•]/', $trimmed)) {
                if (!$inBulletBlock && !empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                }
                $inBulletBlock = true;
                $currentChunk .= $trimmed . "\n";
                continue;
            } else {
                if ($inBulletBlock && !empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                    $currentChunk = '';
                }
                $inBulletBlock = false;
            }

            // Regular paragraph
            if (!empty($trimmed)) {
                $currentChunk .= $trimmed . ' ';
            } elseif (!empty($currentChunk)) {
                $chunks[] = trim($currentChunk);
                $currentChunk = '';
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    public function extractHeadings(array $elements): array
    {
        $headings = [];
        $headingStyles = array_map(fn($n) => "Heading{$n}", range(1, 6));

        foreach ($elements as $element) {
            $isHeading = false;
            $text = '';

            // Case 1: Title object
            if ($element instanceof Title && $element->getDepth() >= 1) {
                $isHeading = true;
                $titleText = $element->getText();
                $text = is_object($titleText)
                    ? $this->extractTextFromContainer($titleText)
                    : $titleText;
            }

            // Case 2: Styled TextRun or ListItemRun
            elseif (method_exists($element, 'getParagraphStyle')) {
                $style = $element->getParagraphStyle();
                $styleName = is_string($style)
                    ? $style
                    : ((is_object($style) && method_exists($style, 'getStyleName')) ? $style->getStyleName() : '');
                if (in_array($styleName, $headingStyles, true)) {
                    $isHeading = true;
                    $text = $this->extractTextFromContainer($element);
                }
            }

            if ($isHeading) {
                $cleaned = trim($text);
                if ($cleaned !== '' && !in_array($cleaned, $headings, true)) {
                    $headings[] = $cleaned;
                }
            }
        }

        return $headings;
    }

    public function extractTextFromContainer($container): string
    {
        $text = '';
        if (method_exists($container, 'getElements')) {
            foreach ($container->getElements() as $child) {
                if (method_exists($child, 'getText')) {
                    $text .= $child->getText() . ' ';
                }
            }
        } elseif (method_exists($container, 'getText')) {
            $text .= $container->getText();
        }
        return trim($text);
    }
}
