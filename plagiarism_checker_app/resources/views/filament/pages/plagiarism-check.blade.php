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

        <!-- Grid Layout: Sources Summary (70%) & Main Content (30%) -->
        <div class="plagiarism-grid">
            <!-- Sources Summary: 70% -->
            <div class="plagiarism-grid-left">
              @if ($giveMeFile)
                    @include('sections.plagiarism-report.download-file')
                @else
                    @include('sections.plagiarism-report.main-content', ['results' => $results]) 
                @endif
            </div>
            <!-- Main content: 30% -->
            <div class="plagiarism-grid-right">
                  @include('sections.plagiarism-report.source-summary', ['results' => $results])                 
            </div>
        </div>

    </div>
</x-filament::page>

@push('styles')
    <style>
      /* Text Colors */
      .text-highlight-0 { color: #ea755b !important; background: rgba(234,117,91,0.18) !important; }
      .text-highlight-1 { color: #faca24 !important; background: rgba(250,202,36,0.18) !important; }
      .text-highlight-2 { color: #31ce52 !important; background: rgba(49,206,82,0.18) !important; }
      .text-highlight-3 { color: #4396ff !important; background: rgba(67,150,255,0.18) !important; }
      .text-highlight-4 { color: #c62be2 !important; background: rgba(198,43,226,0.18) !important; }
      .text-highlight-5 { color: #ff3d85 !important; background: rgba(255,61,133,0.18) !important; }
      .text-highlight-6 { color: #1fc5d4 !important; background: rgba(31,197,212,0.18) !important; }
      .text-highlight-7 { color: #9e7340 !important; background: rgba(158,115,64,0.18) !important; }
      .text-highlight-8 { color: #ffe032 !important; background: rgba(255,224,50,0.18) !important; }
      .text-highlight-9 { color: #949494 !important; background: rgba(148,148,148,0.18) !important; }

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

      /* Light mode backgrounds */
      .bg-exact-match {
          background-color: #ffebee;  /* Light red */
      }
      .bg-paraphrased {
          background-color: #ffe0b2;  /* Light orange */
      }
      .bg-minor-match {
          background-color: #e1bee7;  /* Light purple */
      }
      .bg-original {
          background-color: #e8f5e9;  /* Light green */
      }

      /* Dark mode backgrounds */
      .dark .bg-exact-match {
          background-color: #ef9a9a;  /* Darker red */
      }
      .dark .bg-paraphrased {
          background-color: #ffb74d;  /* Darker orange */
      }
      .dark .bg-minor-match {
          background-color: #ba68c8;  /* Darker purple */
      }
      .dark .bg-original {
          background-color: #81c784;  /* Darker green */
      }

      /* Text color (applies to both modes) */
      .text-black {
          color: #000000;
      }

      /* Plagiarism Grid Layout */
      .plagiarism-grid {
          display: grid;
          grid-template-columns: 1fr;
          gap: 1.5rem;
          align-items: start;
      }
      @media (min-width: 1024px) {
          .plagiarism-grid {
              grid-template-columns: 70% 30%;
          }
      }
      .plagiarism-grid-left {
          width: 100%;
      }
      .plagiarism-grid-right {
          width: 100%;
      }
    </style>
@endpush
