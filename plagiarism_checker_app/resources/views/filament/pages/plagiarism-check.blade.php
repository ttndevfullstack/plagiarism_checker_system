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

        <!-- Report Summary -->
        @include('sections.plagiarism-report.report-summary', ['results' => $results])

        <!-- Sources Summary -->
        @include('sections.plagiarism-report.source-summary', ['results' => $results]) 
        
        <!-- Main content -->
        @include('sections.plagiarism-report.main-content', ['results' => $results]) 

    </div>
</x-filament::page>

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
