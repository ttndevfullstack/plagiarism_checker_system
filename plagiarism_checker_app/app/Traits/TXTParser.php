<?php

namespace App\Traits;

trait TXTParser
{
    public function parseText($filePath, $forPreview = false)
    {
        $content = file_get_contents($filePath);

        if ($forPreview) {
            return [[
                'type' => 'paragraph',
                'content' => [[
                    'text' => $content,
                    'font' => [
                        'bold' => false,
                        'italic' => false,
                        'underline' => false,
                        'color' => '000000',
                        'size' => 12,
                        'name' => 'Times New Roman'
                    ]
                ]]
            ]];
        }

        return $content;
    } 
}