@props(['paragraph' => []])

@if (count(collect($paragraph)->first()['sources'] ?? []) > 0)
    <div
        x-show="showTooltip"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        :style="`position: fixed; left: ${tooltipX}px; top: ${tooltipY + 20}px; transform: translateX(-50%); z-index: 9999;`"
        class="w-96 p-3 text-sm bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600"
    >
        <div class="font-medium mb-2 text-gray-900 dark:text-white">
            Similarity Details
        </div>

        <div class="space-y-2">
            @foreach (collect(collect($paragraph)->first()['sources'])->sortByDesc('similarity_percentage') as $source)
                <div class="border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <a
                                href="{{ $source['url'] }}"
                                target="_blank"
                                class="{{ highlight_text_color($source['color_index'] ?? '') }} font-medium hover:underline"
                            >
                                {{ $source['title'] }}
                            </a>

                            @if ($source['published_date'])
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                    ({{ \Carbon::parse($source['published_date'])->format('Y/m/d H:i:s') }})
                                </span>
                            @endif
                        </div>

                        <span class="{{ highlight_text_color($source['color_index'] ?? '') }} text-sm font-medium">
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