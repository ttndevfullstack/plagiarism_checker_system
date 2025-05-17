<x-filament::card>
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full space-y-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-heroicon-o-document-text class="w-8 h-8 text-primary-500"/>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Download Result File</h3>
                        <p class="text-sm text-gray-500">Your analyzed document with highlighted plagiarism sections</p>
                    </div>
                </div>

                <x-filament::button
                    tag="a"
                    href="{{ $outputPath ?? '#' }}"
                    icon="heroicon-o-arrow-down-tray"
                    class="inline-flex items-center"
                    target="_blank"
                    download
                >
                    Download Document
                </x-filament::button>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <x-heroicon-o-information-circle class="w-5 h-5"/>
                    <span>The highlighted document shows potential plagiarism matches in different colors based on similarity percentage.</span>
                </div>
            </div>
        </div>
    </div>
</x-filament::card>
