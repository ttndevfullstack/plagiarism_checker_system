<?php

namespace App\Traits;

trait SeedValidator
{
    public function isSkipSeed(string $model): bool
    {
        if (! class_exists($model)) {
            throw new InvalidArgumentException("The model class `{$model}` does not exist.");
        }

        if ($this->isSeeded($model)) {
            $modelName = class_basename($model);
            echo "✅ The `{$modelName}` model is already seeded with data => Skipped...\n";
            return true;
        }

        return false;
    }

    public function isSeeded(string $model): bool
    {
        return $model::query()->exists();
    }
}
