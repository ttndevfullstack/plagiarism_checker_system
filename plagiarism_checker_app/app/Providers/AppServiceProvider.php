<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ImageManager::class, static fn () => new ImageManager(
            config('image.driver') === 'imagick' ? new ImagickDriver() : new GdDriver()
        ));
    }

    public function boot(): void
    {
        //

    }
}
