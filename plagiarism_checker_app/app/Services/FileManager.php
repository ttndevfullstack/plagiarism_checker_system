<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class FileManager
{
    protected string $disk;

    /**
     * Constructor to initialize the FileManager with a specific disk.
     *
     * @param string $disk
     */
    public function __construct(string $disk = 'local')
    {   
        $this->disk = $disk;
    }

    /**
     * Upload a file to the storage.
     *
     * @param mixed $file  Uploaded file instance.
     * @param string $path Path where the file should be stored.
     * @param string $initials Prefix for the file name.
     * @return string  File path of the uploaded file.
     * @throws Exception If file upload fails.
     */
    public function upload($file, string $path, string $fileName): string
    {
        try {
            $storedFile = $file->storeAs($path, $fileName, $this->disk);

            if (!$storedFile) {
                throw new Exception("File upload failed.");
            }

            return str_replace('\\', '/', $storedFile);
        } catch (\Throwable $e) {
            Log::error('Error during file upload: ' . $e->getMessage());

            throw new Exception("File Uploading Error: " . $e->getMessage());
        }
    }

    /**
     * Download a file from storage.
     *
     * @param string $path
     * @throws Exception If file not found or download fails.
     */
    public function download(string $path): mixed
    {
        try {
            if (!$this->exists($path)) {
                throw new Exception("File not found: $path");
            }

            return Storage::disk($this->disk)->download($path);
        } catch (\Throwable $e) {
            Log::error('Error during file download: ' . $e->getMessage());

            throw new Exception("File Download Error: " . $e->getMessage());
        }
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @throws Exception If deletion fails.
     */
    public function delete(string $path): bool
    {
        try {
            return Storage::disk($this->disk)->delete($path);
        } catch (\Throwable $e) {
            Log::error('Error during file deletion: ' . $e->getMessage());

            throw new Exception("File Deletion Error: " . $e->getMessage());
        }
    }

    /**
     * Check if a file exists in storage.
     *
     * @param string $path
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get the URL of a file (useful for public disks like S3).
     *
     * @param string $path
     * @throws Exception If unable to get the URL.
     */
    public function getUrl(string $path): string
    {
        try {
            return Storage::disk($this->disk)->url($path);
        } catch (\Throwable $e) {
            Log::error('Error getting file URL: ' . $e->getMessage());

            throw new Exception("Error getting file URL: " . $e->getMessage());
        }
    }

    /**
     * Move or rename a file in storage.
     *
     * @param string $oldPath
     * @param string $newPath
     */
    public function move(string $oldPath, string $newPath): bool
    {
        try {
            return Storage::disk($this->disk)->move($oldPath, $newPath);
        } catch (\Throwable $e) {
            Log::error('Error moving file: ' . $e->getMessage());

            throw new Exception("File Move Error: " . $e->getMessage());
        }
    }

    /**
     * Copy a file within storage.
     *
     * @param string $sourcePath
     * @param string $destinationPath
     */
    public function copy(string $sourcePath, string $destinationPath): bool
    {
        try {
            return Storage::disk($this->disk)->copy($sourcePath, $destinationPath);
        } catch (\Throwable $e) {
            Log::error('Error copying file: ' . $e->getMessage());

            throw new Exception("File Copy Error: " . $e->getMessage());
        }
    }

    /**
     * Get metadata of a file (e.g., size, last modified time).
     *
     * @param string $path
     * @return array<string, mixed>
     */
    public function getMetadata(string $path): array
    {
        try {
            $size = Storage::disk($this->disk)->size($path);
            $lastModified = Storage::disk($this->disk)->lastModified($path);

            return [
                'size' => $size,
                'last_modified' => $lastModified,
            ];
        } catch (\Throwable $e) {
            Log::error('Error getting file metadata: ' . $e->getMessage());

            throw new Exception("Error getting file metadata: " . $e->getMessage());
        }
    }

    /**
     * List all files in a directory.
     *
     * @param string $directory
     * @param bool $recursive
     * @return array<string, mixed>
     */
    public function listFiles(string $directory, bool $recursive = false): array
    {
        return Storage::disk($this->disk)->files($directory, $recursive);
    }

    /**
     * List all directories in a directory.
     *
     * @param string $directory
     * @param bool $recursive
     * @return array<string, mixed>
     */
    public function listDirectories(string $directory, bool $recursive = false): array
    {
        return Storage::disk($this->disk)->directories($directory, $recursive);
    }

    /**
     * Create a new directory.
     *
     * @param string $directory
     */
    public function createDirectory(string $directory): bool
    {
        return Storage::disk($this->disk)->makeDirectory($directory);
    }

    /**
     * Delete a directory.
     *
     * @param string $directory
     */
    public function deleteDirectory(string $directory): bool
    {
        return Storage::disk($this->disk)->deleteDirectory($directory);
    }
}
