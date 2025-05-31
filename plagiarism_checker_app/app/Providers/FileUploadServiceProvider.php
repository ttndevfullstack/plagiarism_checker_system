<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class FileUploadServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Create downloads directory if it doesn't exist
        if (!Storage::disk('public')->exists('downloads')) {
            Storage::disk('public')->makeDirectory('downloads');
        }

        // Move files from livewire-tmp to downloads
        $this->app->terminating(function () {
            $files = Storage::disk('local')->files('livewire-tmp');
            foreach ($files as $file) {
                if (Storage::disk('local')->exists($file)) {
                    $filename = basename($file);
                    if (!Storage::disk('public')->exists('downloads/' . $filename)) {
                        Storage::disk('local')->copy(
                            $file,
                            'public/downloads/' . $filename
                        );
                    }
                }
            }
        });
    }
}
