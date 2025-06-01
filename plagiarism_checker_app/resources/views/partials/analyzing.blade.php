<div class="fixed inset-0 bg-white dark:bg-gray-900 z-50">
    <div class="flex flex-col items-center justify-center min-h-screen p-6">
        <!-- Logo placeholder -->
        <div class="w-16 h-16 mb-8">
            <svg class="w-full h-full text-primary-600" viewBox="0 0 24 24" fill="none">
                <path d="M12 4L3 8L12 12L21 8L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M3 8V16L12 20L21 16V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>

        <!-- Progress bar -->
        <div class="w-64 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mb-4 overflow-hidden">
            <div class="h-full bg-primary-600 animate-progress-bar"></div>
        </div>

        <!-- Loading text -->
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            Analyzing Document
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-sm">
            We're checking your document for potential matches. This may take a few moments.
        </p>
    </div>
</div>

@push('styles')
    <style>
        @keyframes progress-bar {
            0% { width: 0; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        .animate-progress-bar {
            animation: progress-bar 3s ease-in-out infinite;
        }
    </style>
@endpush