<x-filament::page>
    <div class="space-y-6">
        <x-filament::card>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">Plagiarism Report</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generated {{ $results['processed_at'] ?? now()->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div
                                class="text-2xl font-bold @if ($results['total_similarity_percentage'] > 70) text-danger-600 @elseif($results['total_similarity_percentage'] > 30) text-warning-600 @else text-success-600 @endif">
                                {{ $results['total_similarity_percentage'] }}%
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Similarity Score</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ count($results['sources_summary']) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sources Found</div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border p-4 @if ($results['total_similarity_percentage'] > 70) bg-danger-50 dark:bg-danger-900/30 border-danger-200 dark:border-danger-700 @elseif($results['total_similarity_percentage'] > 30) bg-warning-50 dark:bg-warning-900/30 border-warning-200 dark:border-warning-700 @else bg-success-50 dark:bg-success-900/30 border-success-200 dark:border-success-700 @endif">
                    <div class="font-medium">Verdict:</div>
                    <p class="text-gray-800 dark:text-gray-200">{{ $results['overall_verdict'] }}</p>
                </div>
            </div>
        </x-filament::card>

        <x-filament::card>
            <!-- Sources Summary -->
            <div class="lg:w-[20%]">
                <div class="sticky top-4 space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">Sources Summary</h4>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Source</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Matches</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                        Highest</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($results['sources_summary'] as $source)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 max-w-[150px]">
                                            <a href="{{ $source['url'] }}" target="_blank"
                                                class="text-base text-primary-600 dark:text-primary-400 hover:underline truncate block">
                                                {{ $source['title'] }}
                                            </a>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $source['url'] }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                            {{ $source['total_matched'] }}</td>
                                        <td
                                            class="px-4 py-3 @if ($source['highest_similarity'] > 90) text-danger-600 dark:text-danger-400 @elseif($source['highest_similarity'] > 60) text-warning-600 dark:text-warning-400 @else text-primary-600 dark:text-primary-400 @endif">
                                            {{ $source['highest_similarity'] }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-filament::card>

        @if ($results)
            <x-filament::card>
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Main content -->
                    <div class="w-full space-y-4">
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-4">Content Analysis</h4>
                            <div class="rounded border p-4 bg-gray-50 dark:bg-gray-800">
                                @if (isset($preview_content))
                                    <div class="relative my-2">
                                        {!! nl2br(e($preview_content['content'])) !!}
                                    </div>
                                @else
                                    @foreach ($results['paragraphs'] as $paragraph)
                                        <div x-data="{ showTooltip: false }" x-on:mouseover="showTooltip = true"
                                            x-on:mouseleave="showTooltip = false" class="relative my-2">
                                            <p
                                                class="border-l-4 @if ($paragraph['similarity_percentage'] > 90) bg-danger-100 dark:bg-danger-900/50 border-danger-500 dark:border-danger-600 @elseif($paragraph['similarity_percentage'] > 60) bg-warning-100 dark:bg-warning-900/50 border-warning-500 dark:border-warning-600 @elseif($paragraph['similarity_percentage'] > 0) bg-primary-50 dark:bg-primary-900/50 border-primary-500 dark:border-primary-600 @endif p-2 rounded text-gray-800 dark:text-gray-200">
                                                {{ $paragraph['text'] }}
                                            </p>

                                            @if ($paragraph['similarity_percentage'] > 0)
                                                <div x-show="showTooltip"
                                                    class="absolute z-10 w-64 p-2 mt-1 text-sm bg-white dark:bg-gray-700 rounded shadow-lg border border-gray-200 dark:border-gray-600">
                                                    <div class="font-medium mb-1 text-gray-900 dark:text-white">
                                                        {{ $paragraph['similarity_percentage'] }}% Similar</div>
                                                    @if (count($paragraph['sources']) > 0)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Found
                                                            in:</div>
                                                        <ul class="space-y-1">
                                                            @foreach ($paragraph['sources'] as $source)
                                                                <li class="truncate">
                                                                    <a href="{{ $source['url'] }}" target="_blank"
                                                                        class="text-primary-600 dark:text-primary-400 hover:underline">
                                                                        {{ $source['title'] }}
                                                                        ({{ $source['similarity_percentage'] }}%)
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::card>
        @else
            <x-filament::tabs>
                <x-filament::tabs.item active wire:click="$set('activeTab', 'upload')">
                    Upload Document
                </x-filament::tabs.item>
                <x-filament::tabs.item wire:click="$set('activeTab', 'paste')">
                    Paste Text
                </x-filament::tabs.item>
                <x-filament::tabs.item wire:click="$set('activeTab', 'url')">
                    Check URL
                </x-filament::tabs.item>
            </x-filament::tabs>

            <x-filament::card>
                @if ($activeTab === 'upload')
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">
                            Upload Document
                        </h2>
                        <div
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                            <input type="file" class="hidden" wire:model="document">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                wire:click="$refs.documentUpload.click()">
                                Select File
                            </button>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Supported formats: PDF, DOCX, TXT
                            </p>
                        </div>
                    </div>
                @elseif($activeTab === 'paste')
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">
                            Paste Text
                        </h2>
                        <textarea wire:model="content" rows="10"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                            placeholder="Paste your content here..."></textarea>
                    </div>
                @elseif($activeTab === 'url')
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">
                            Check URL
                        </h2>
                        <input type="text" wire:model="url"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                            placeholder="https://example.com">
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <button wire:click="checkPlagiarism" wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <span wire:loading.remove>Check for Plagiarism</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </x-filament::card>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips for paragraph hover effects
                document.querySelectorAll('[x-data]').forEach(el => {
                    Alpine.initializeComponent(el);
                });
            });
        </script>
    @endpush
</x-filament::page>
