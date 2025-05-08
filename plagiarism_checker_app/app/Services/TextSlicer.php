<?php

namespace App\Services;

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
}
