{{-- <x-filament::card>
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
</x-filament::card> --}}

<x-filament::card style="background: linear-gradient(135deg, #f5f7fa 0%, #e4f0fb 100%); border: none; border-radius: 12px; overflow: hidden;">
    <div style="text-align: center; padding: 2.5rem 1.5rem; position: relative;">
        <!-- Decorative elements -->
        <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: radial-gradient(circle, rgba(46,204,113,0.1) 0%, rgba(46,204,113,0) 70%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 80px; height: 80px; background: radial-gradient(circle, rgba(52,152,219,0.1) 0%, rgba(52,152,219,0) 70%);"></div>
        
        <!-- Success Icon with animation -->
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 50%; background-color: #e6f7ee; margin-bottom: 1.5rem; animation: pulse 2s infinite;">
            <svg style="width: 40px; height: 40px; color: #2ecc71;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Congratulation Message -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.75rem; font-weight: 700; color: #2c3e50; margin-bottom: 0.75rem;">
                Congratulations! 🎉
            </h2>
            <p style="font-size: 1.1rem; color: #7f8c8d; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                Your document has passed the plagiarism check with outstanding results. 
                Your work demonstrates excellent originality and academic integrity.
            </p>
        </div>

        <!-- Statistics Grid -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; max-width: 800px; margin: 0 auto; background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <!-- Originality Score -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #2ecc71; margin-bottom: 0.5rem;">
                    {{ $results['originality_score'] ?? '100' }}%
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Originality Score
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['originality_score'] ?? 100) }}%; background: #2ecc71; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Similarity Score -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #e74c3c; margin-bottom: 0.5rem;">
                    {{ $results['similarity_score'] ?? '0' }}%
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Similarity Found
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['total_similarity_percentage'] ?? 0) }}%; background: #e74c3c; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Sources Checked -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #3498db; margin-bottom: 0.5rem;">
                    {{ $results['source_count'] ?? '0' }}
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Sources Checked
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #3498db; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Words Analyzed -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.5rem;">
                    {{ $results['words_analyzed'] ?? '0' }}
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Words Analyzed
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #9b59b6; border-radius: 2px;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</x-filament::card>