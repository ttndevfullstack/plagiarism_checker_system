<x-filament::page>
    <div class="space-y-6">
        @if ($error)
            <div
                class="rounded-lg bg-danger-50 dark:bg-danger-900/50 p-4 border border-danger-300 dark:border-danger-600">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-heroicon-m-x-circle class="h-5 w-5 text-danger-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-danger-800 dark:text-danger-200">Error</h3>
                        <div class="mt-2 text-sm text-danger-700 dark:text-danger-300">
                            {{ $error }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($isLoading)
            <div class="flex items-center justify-center p-6">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary-600 border-r-transparent">
                    </div>
                    <span class="text-lg font-medium">Analyzing content for plagiarism...</span>
                </div>
            </div>
        @endif

        <!-- Plagiarism Report -->
        <x-filament::card>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">Plagiarism Report</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Generated {{ now()->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-4 gap-6">
                        <div class="text-center">
                            <div
                                class="text-2xl font-bold {{ highlight_text_color($results['total_similarity_percentage'] ?? 0) }}">
                                {{ $results['total_similarity_percentage'] ?? 0 }}%
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Similarity Score</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ count($results['sources_summary'] ?? []) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Sources Found</div>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-lg border p-4 @if ($results['total_similarity_percentage'] ?? 0 > 70) bg-danger-50 dark:bg-danger-900/30 border-danger-200 dark:border-danger-700 @elseif($results['total_similarity_percentage'] ?? 0 > 30) bg-warning-50 dark:bg-warning-900/30 border-warning-200 dark:border-warning-700 @else bg-success-50 dark:bg-success-900/30 border-success-200 dark:border-success-700 @endif">
                    <div class="font-medium">Verdict:</div>
                    <p class="text-gray-800 dark:text-gray-200">{{ $results['overall_verdict'] ?? '' }}</p>
                </div>
            </div>
        </x-filament::card>

        <!-- Sources Summary -->
        <x-filament::card>
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
                                @foreach ($results['sources_summary'] ?? [] as $source)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 max-w-[150px]">
                                            <a href="{{ $source['url'] }}" target="_blank"
                                                class="{{ highlight_text_color($source['highest_similarity'] ?? 0) }} text-base text-primary-600 dark:text-primary-400 hover:underline truncate block">
                                                {{ $source['title'] }}
                                            </a>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $source['url'] }}</div>
                                        </td>
                                        <td
                                            class="{{ highlight_text_color($source['highest_similarity'] ?? 0) }} px-4 py-3">
                                            {{ $source['total_matched'] }}</td>
                                        <td
                                            class="{{ highlight_text_color($source['highest_similarity'] ?? 0) }} px-4 py-3">
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

        <!-- Main content -->
        <x-filament::card>
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="w-full space-y-4">
                    <div class="space-y-2">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-4">Content Analysis</h4>
                        <div class="rounded border p-4 bg-gray-50 dark:bg-gray-800">
                            @if (! is_null($preview_content))
                                <div class="prose prose-lg max-w-none dark:prose-invert">
                                    {!! $preview_content['content'] !!}
                                </div>
                            @endif

                            {{-- @foreach ($results['paragraphs'] ?? [] as $paragraph)
                                @php
                                    // Add glow effect for high similarity
                                    $glowClass =
                                        $paragraph['similarity_percentage'] > 70
                                            ? 'shadow-danger-200/50 dark:shadow-danger-800/30'
                                            : ($paragraph['similarity_percentage'] > 50
                                                ? 'shadow-warning-200/40 dark:shadow-warning-800/20'
                                                : '');
                                @endphp

                                <div
                                    class="relative my-2 group"
                                    x-data="{ showTooltip: false, tooltipX: 0, tooltipY: 0 }"
                                    @mousemove="tooltipX = $event.clientX; tooltipY = $event.clientY"
                                    @mouseover="showTooltip = true" @mouseleave="showTooltip = false"
                                >
                                    <div class="flex items-start gap-2">
                                        <!-- Highlighted text -->
                                        <div class="flex-1 min-w-0">
                                            <p class="{{ highlight_text_background($paragraph['similarity_percentage'] ?? 0) }} {{ $glowClass }} p-1 transition-all duration-200 group-hover:shadow-sm">
                                                {{ $paragraph['text'] }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($paragraph['similarity_percentage'] > 0 && count($paragraph['sources']) > 0)
                                        <div x-show="showTooltip" x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            :style="`position: fixed; left: ${tooltipX}px; top: ${tooltipY + 20}px; transform: translateX(-50%);`"
                                            class="z-50 w-96 p-3 text-sm bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600"
                                        >
                                            <div class="font-medium mb-2 text-gray-900 dark:text-white">
                                                Similarity Details
                                            </div>

                                            <div class="space-y-2">
                                                @foreach ($paragraph['sources'] as $source)
                                                    <div class="border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0 last:pb-0">
                                                        <div class="flex justify-between items-start gap-4">
                                                            <div>
                                                                <a
                                                                    href="{{ $source['url'] }}"
                                                                    target="_blank"
                                                                    class="{{ highlight_text_color($source['similarity_percentage'] ?? 0) }} font-medium hover:underline"
                                                                >
                                                                    {{ $source['title'] }}
                                                                </a>

                                                                @if ($source['published_date'])
                                                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                                        ({{ \Carbon::parse($source['published_date'])->format('Y/m/d H:i:s') }})
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <span class="{{ highlight_text_color($source['similarity_percentage'] ?? 0) }} text-sm font-medium">
                                                                {{ $source['similarity_percentage'] }}%
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                                                            {{ $source['url'] }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::card>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Alpine.js components
                document.querySelectorAll('[x-data]').forEach(el => {
                    Alpine?.initializeComponent(el);
                });

                // Smooth scroll to first high similarity paragraph
                const firstHighSimilarity = document.querySelector('.bg-danger-100\\/80, .bg-danger-50\\/70');
                if (firstHighSimilarity) {
                    firstHighSimilarity.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Pulse animation for attention
                    firstHighSimilarity.classList.add('animate-pulse');
                    setTimeout(() => {
                        firstHighSimilarity.classList.remove('animate-pulse');
                    }, 2000);
                }
            });
        </script>
    @endpush
</x-filament::page>

@push('styles')
    <style>
        /* Text Colors */
      .text-danger-600 { color: #dc2626; }           /* Tailwind's red-600 */
      .dark .text-danger-400 { color: #f87171; }

      .text-pink-600 { color: #db2777; }
      .dark .text-pink-400 { color: #f9a8d4; }

      .text-warning-600 { color: #d97706; }          /* Tailwind's amber-600 */
      .dark .text-warning-400 { color: #fbbf24; }

      .text-success-600 { color: #16a34a; }          /* Tailwind's green-600 */
      .dark .text-success-400 { color: #4ade80; }

      /* Background Colors */
      .bg-danger-100 { background-color: #fee2e2; }
      .dark .bg-danger-400 { background-color: #f87171; }

      .bg-pink-100 { background-color: #fce7f3; }
      .dark .bg-pink-400 { background-color: #f9a8d4; }

      .bg-warning-100 { background-color: #fef3c7; }
      .dark .bg-warning-400 { background-color: #fbbf24; }

      .bg-success-100 { background-color: #dcfce7; }
      .dark .bg-success-400 { background-color: #4ade80; }

      /* Always apply white text */
      .text-white { color: #ffffff; }
      .text-black { color: #000000; }
    </style>
@endpush
