<div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
    <div class="flex items-center space-x-4">
        <x-filament::button id="prev-page-{{ $id }}" icon="heroicon-m-arrow-left" class="focus:outline-none"
            style="margin-right: 10px">
            Prev
        </x-filament::button>

        <x-filament::button id="next-page-{{ $id }}" icon="heroicon-m-arrow-right" icon-position="after" class="focus:outline-none">
            Next
        </x-filament::button>
    </div>

    <div class="text-sm text-gray-600 dark:text-gray-300">
        Page <span class="page-num font-medium"></span> of <span class="page-count font-medium"></span>
    </div>
</div>
