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
            @include('partials.analyzing')
        @endif

        <!-- Report Summary -->
        @include('sections.plagiarism-report.report-summary', ['results' => $results])

        <!-- Grid Layout: PDF Preview (75%) & Sources Summary (25%) -->
        <div class="plagiarism-grid">
            <!-- PDF Preview: 75% -->
            <div class="plagiarism-grid-left">
                <x-filament::card class="h-full">
                    <div class="filament-page">
                        <!-- Report Header -->
                        @include('partials.report-header', ['id' => 'top'])

                        <canvas id="pdf-render"></canvas>

                        <!-- Report Header -->
                        @include('partials.report-header', ['id' => 'bottom'])
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
    <script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdfjs/pdf.worker.min.js') }}';
        window.pdfUrl = '{{ $filePath }}';
        window.plagiarismData = @json($results['data'] ?? []);
    </script>
    <script src="{{ asset('js/pdf-viewer.js') }}"></script>
@endpush

@push('styles')
    <style>
        /* Text Colors */
        .text-highlight-0 { color: #ea755b !important; }
        .text-highlight-1 { color: #faca24 !important; }
        .text-highlight-2 { color: #31ce52 !important; }
        .text-highlight-3 { color: #4396ff !important; }
        .text-highlight-4 { color: #c62be2 !important; }
        .text-highlight-5 { color: #ff3d85 !important; }
        .text-highlight-6 { color: #1fc5d4 !important; }
        .text-highlight-7 { color: #9e7340 !important; }
        .text-highlight-8 { color: #ffe032 !important; }
        .text-highlight-9 { color: #949494 !important; }
        
        .text-highlight-0.background { background: rgba(234,117,91,0.18) !important; }
        .text-highlight-1.background { background: rgba(250,202,36,0.18) !important; }
        .text-highlight-2.background { background: rgba(49,206,82,0.18) !important; }
        .text-highlight-3.background { background: rgba(67,150,255,0.18) !important; }
        .text-highlight-4.background { background: rgba(198,43,226,0.18) !important; }
        .text-highlight-5.background { background: rgba(255,61,133,0.18) !important; }
        .text-highlight-6.background { background: rgba(31,197,212,0.18) !important; }
        .text-highlight-7.background { background: rgba(158,115,64,0.18) !important; }
        .text-highlight-8.background { background: rgba(255,224,50,0.18) !important; }
        .text-highlight-9.background { background: rgba(148,148,148,0.18) !important; }

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
