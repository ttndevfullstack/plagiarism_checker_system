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
