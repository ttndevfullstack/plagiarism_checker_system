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

        <!-- Grid Layout: PDF Preview (75%) & Sources Summary (25%) -->
        <div class="plagiarism-grid">
            <!-- PDF Preview: 75% -->
            <div class="plagiarism-grid-left">
                <x-filament::card class="h-full">
                    <h1>{{ $filePath }}</h1>
                    <div class="filament-page">
                        <div class="top-bar">
                            <button class="btn" id="prev-page">
                                <i class="fas fa-arrow-circle-left"></i> Prev Page
                            </button>
                            <button class="btn" id="next-page">
                                Next Page <i class="fas fa-arrow-circle-right"></i>
                            </button>
                            <span class="page-info">
                                Page <span id="page-num"></span> of <span id="page-count"></span>
                            </span>
                        </div>

                        <canvas id="pdf-render"></canvas>
                    </div>
                </x-filament::card>
            </div>
            <!-- Sources Summary: 25% -->
            <div class="plagiarism-grid-right">
                @include('sections.plagiarism-report.source-summary', ['results' => $results])
            </div>
        </div>
    </div>
</x-filament::page>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        window.pdfUrl = '{{ $filePath }}';
        window.plagiarismData = @json($results['data'] ?? []);
    </script>
    <script src="{{ asset('js/pdf-viewer.js') }}"></script>
@endpush

@push('styles')
    <style>
        /* Text Colors */
        .text-danger-600 {
            color: #dc2626;
        }

        /* Tailwind's red-600 */
        .dark .text-danger-400 {
            color: #f87171;
        }

        .text-pink-600 {
            color: #db2777;
        }

        .dark .text-pink-400 {
            color: #f9a8d4;
        }

        .text-warning-600 {
            color: #d97706;
        }

        /* Tailwind's amber-600 */
        .dark .text-warning-400 {
            color: #fbbf24;
        }

        .text-success-600 {
            color: #16a34a;
        }

        /* Tailwind's green-600 */
        .dark .text-success-400 {
            color: #4ade80;
        }

        /* Background Colors */
        .bg-danger-100 {
            background-color: #fee2e2;
        }

        .dark .bg-danger-400 {
            background-color: #f87171;
        }

        .bg-pink-100 {
            background-color: #fce7f3;
        }

        .dark .bg-pink-400 {
            background-color: #f9a8d4;
        }

        .bg-warning-100 {
            background-color: #fef3c7;
        }

        .dark .bg-warning-400 {
            background-color: #fbbf24;
        }

        .bg-success-100 {
            background-color: #dcfce7;
        }

        .dark .bg-success-400 {
            background-color: #4ade80;
        }

        /* Always apply white text */
        .text-white {
            color: #ffffff;
        }

        .text-black {
            color: #000000;
        }

        /* Light mode backgrounds */
        .bg-exact-match {
            background-color: #ffebee;
            /* Light red */
        }

        .bg-paraphrased {
            background-color: #ffe0b2;
            /* Light orange */
        }

        .bg-minor-match {
            background-color: #e1bee7;
            /* Light purple */
        }

        .bg-original {
            background-color: #e8f5e9;
            /* Light green */
        }

        /* Dark mode backgrounds */
        .dark .bg-exact-match {
            background-color: #ef9a9a;
            /* Darker red */
        }

        .dark .bg-paraphrased {
            background-color: #ffb74d;
            /* Darker orange */
        }

        .dark .bg-minor-match {
            background-color: #ba68c8;
            /* Darker purple */
        }

        .dark .bg-original {
            background-color: #81c784;
            /* Darker green */
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
            margin: 1rem;
        }

        @media (min-width: 1024px) {
            .plagiarism-grid {
                grid-template-columns: 75% 25%;
            }
        }

        .plagiarism-grid-left {
            width: 100%;
            min-height: 800px;
        }

        .plagiarism-grid-right {
            width: 100%;
            position: sticky;
            top: 1rem;
        }

        #pdf-render {
            width: 100%;
            height: auto;
        }



        .top-bar {
            background: #333;
            color: #fff;
            padding: 1rem;
        }

        .btn {
            background: coral;
            color: #fff;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 0.7rem 2rem;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .page-info {
            margin-left: 1rem;
        }

        .error {
            background: orangered;
            color: #fff;
            padding: 1rem;
        }

        .textLayer {
            position: absolute;
            text-align: initial;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            line-height: 1.0;
            pointer-events: none;
        }

        .textLayer>span {
            color: transparent;
            position: absolute;
            white-space: pre;
            cursor: text;
            transform-origin: 0% 0%;
        }

        .textLayer .highlight {
            margin: -1px;
            padding: 1px;
            background-color: rgb(180, 0, 170);
            border-radius: 4px;
        }

        .textLayer .highlight.selected {
            background-color: rgb(0, 100, 0);
        }

        .textLayer ::selection {
            background: rgb(0, 0, 255, 0.2);
        }

        .pdf-annotation {
            position: absolute;
            border-radius: 3px;
            pointer-events: auto;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pdf-annotation:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }

        #pdf-tooltip {
            position: fixed;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 4px;
            max-width: 300px;
            font-size: 12px;
            z-index: 1000;
            pointer-events: none;
        }

        .filament-page {
            position: relative;
        }

        #pdf-render {
            position: relative;
            z-index: 1;
        }
    </style>
@endpush
