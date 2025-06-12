<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanStorageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-storage';

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

        $this->info('Cleaned files and folders in: livewire-tmp, downloads.');
    }
}
