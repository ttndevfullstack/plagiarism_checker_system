<?php

namespace App\Services\Documents;

use App\Services\FileManager;
use ZipArchive;

class DocumentBatchService
{
    public function __construct(private FileManager $fileManager) {}

    public function extractFileAndArchive(string $filePath): string
    {
        $extractPath = $this->generateExtractPath();

        if (str_ends_with(strtolower($filePath), '.zip')) {
            $this->extractZipFile($filePath, $extractPath);
        } else if (str_ends_with(strtolower($filePath), '.rar') && class_exists('RarArchive')) {
            $this->extractRARFile($filePath, $extractPath);
        } else {
            throw new \InvalidArgumentException("Unsupported archive format: $filePath");
        }

        return $extractPath;
    }

    public function extractZipFile(string $filePath, string $extractPath): void
    {
        $zip = new ZipArchive;

        if (! file_exists($filePath)) {
            throw new \InvalidArgumentException("File does not exist: $filePath");
        }

        if ($zip->open($filePath) === false) {
            throw new \RuntimeException("Failed to open ZIP archive: $filePath");
        }

        $zip->extractTo($extractPath);
        $zip->close();
    }

    public function extractRARFile(string $filePath, string $extractPath): void
    {
        $rar = RarArchive::open($filePath);

        if ($rar === false) {
            throw new RuntimeException("Failed to open RAR archive: $filePath");
        }

        foreach ($rar->getEntries() as $entry) {
            if ($entry === false) {
                continue;
            }
            $entry->extract($extractPath);
        }

        $rar->close();
    }

    public function generateExtractPath(): string
    {
        $extractPath = storage_path('app/temp/' . uniqid());
        mkdir($extractPath, 0755, true);

        return $extractPath;
    }
}
