<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearStorageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paths = [
            storage_path('app/livewire-tmp'),
            storage_path('app/public/downloads'),
            storage_path('app/public/media'),
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                // Delete all files
                foreach (File::files($path) as $file) {
                    File::delete($file);
                }
                // Delete all subdirectories
                foreach (File::directories($path) as $dir) {
                    File::deleteDirectory($dir);
                }
            }
        }

        $this->info('Cleared files and folders in: livewire-tmp, downloads, media.');
    }
}
