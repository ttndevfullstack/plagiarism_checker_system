<?php

namespace App\Filament\Components\Filters;

use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class BaseFilterGroup
{
    /**
     * @return array<DateRangeFilter>
     */
    public static function show(): array
    {
        return [
            DateRangeFilter::make('created_at')
                ->label(__('Created At'))
                ->placeholder(__('Input date range')),
        ];
    }
}
