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

        <div class="rounded-lg border p-4 {{ highlight_text_color($results['total_similarity_percentage'] ?? 0) }}">
            <div class="font-medium">Verdict:</div>
            <p class="text-gray-800 dark:text-gray-200">{{ $results['overall_verdict'] ?? '' }}</p>
        </div>
    </div>
</x-filament::card>
