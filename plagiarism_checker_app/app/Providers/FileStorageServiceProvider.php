<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class FileStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Create downloads directory if it doesn't exist
        $downloadsPath = Storage::disk('public')->path('downloads');
        if (!file_exists($downloadsPath)) {
            mkdir($downloadsPath, 0755, true);
        }

        // Clear temporary upload files periodically
        if (rand(1, 100) === 1) {
            $this->clearTemporaryUploads();
        }
    }

    private function clearTemporaryUploads(): void
    {
        $tempPath = Storage::disk('local')->path('livewire-tmp');
        if (file_exists($tempPath)) {
            $files = glob($tempPath . '/*');
            $now = time();
            foreach ($files as $file) {
                if (is_file($file)) {
                    if ($now - filemtime($file) > 3600) { // Remove files older than 1 hour
                        unlink($file);
                    }
                }
            }
        }
    }
}
