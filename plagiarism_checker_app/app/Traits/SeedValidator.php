<?php

namespace App\Traits;

trait SeedValidator
{
    /**
     * Check if the given model has already been seeded.
     */
    public function isSeeded(string $model): bool
    {
        return $model::query()->exists();
    }
}
