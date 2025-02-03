<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;

class ImageWriter
{
    private const DEFAULT_MAX_WIDTH = 500;

    private const DEFAULT_QUALITY = 80;

    private string $supportedFormat = 'jpg';

    public function __construct(private readonly ImageManager $imageManager)
    {
        $this->supportedFormat = self::getSupportedFormat();
    }

    public function write(string $destination, object|string $source, array $config = []): void
    {
        $image = $this->imageManager
            ->read($source)
            ->resize(
                $config['max_width'] ?? self::DEFAULT_MAX_WIDTH,
                null,
                static function ($constraint): void {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                }
            );

        if (isset($config['blur'])) {
            $image->blur($config['blur']);
        }

        $image->save($destination, $config['quantity'] ?? self::DEFAULT_QUALITY, $this->supportedFormat);
    }

    private static function getSupportedFormat(): string
    {
        return Arr::get(gd_info(), 'WebP Support') ? 'webp' : 'jpg';
    }
}
