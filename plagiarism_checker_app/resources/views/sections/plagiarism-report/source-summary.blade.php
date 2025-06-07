<x-filament::card>
    <div class="lg:w-[20%]">
        <div class="sticky top-4 space-y-4">
            <h4 class="font-medium text-gray-900 dark:text-white">Sources Summary</h4>
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                ID</th>
                            <th
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Source</th>
                            <th
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Similarity</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if (count($results['sources_summary'] ?? []) < 1)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-2 py-3" colspan="3">No have any source matched.</td>
                            </tr>
                        @else
                            @foreach ($results['sources_summary'] ?? [] as $source)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="{{ highlight_text_color($source['source_similarity'] ?? 0) }} px-2 py-3 max-w-[150px]">
                                        {{ $source['document_id'] }}
                                    </td>
                                    <td
                                        class="{{ highlight_text_color($source['source_similarity'] ?? 0) }} px-2 py-3">
                                        <a href="{{ $source['url'] }}" target="_blank"
                                            class="{{ highlight_text_color($source['source_similarity'] ?? 0) }} text-base text-primary-600 dark:text-primary-400 hover:underline truncate block max-w-[200px]"
                                            title="{{ $source['title'] }}">
                                            {{ \Illuminate\Support\Str::limit($source['title'], 15) }}
                                        </a>
                                    </td>
                                    <td
                                        class="{{ highlight_text_color($source['source_similarity'] ?? 0) }} px-2 py-3">
                                        {{ $source['source_similarity'] }}%
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::card>
