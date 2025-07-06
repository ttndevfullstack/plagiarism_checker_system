<div class="plagiarism-loader">
    <div class="loader-content">
        <!-- Document with scanning animation -->
        <div class="document-container">
            <svg class="w-full h-full text-primary-600" viewBox="0 0 24 24" fill="none">
                <path d="M12 4L3 8L12 12L21 8L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M3 8V16L12 20L21 16V8" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>

        @if ($error)
            <p class="loading-subtext error-message">
                <span class="error-icon">&#9888;</span>
                <span class="highlight">Error:</span> {{ $error }}
            </p>
        @else
            <!-- Progress indicator -->
            <div class="progress-container">
                <div class="progress-labels">
                    <span>0%</span>
                    <span class="progress-counter">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <!-- Loading text with animations -->
            <div class="loading-text">
                <h2 class="loading-heading">
                    <span class="letter">A</span>
                    <span class="letter">n</span>
                    <span class="letter">a</span>
                    <span class="letter">l</span>
                    <span class="letter">y</span>
                    <span class="letter">z</span>
                    <span class="letter">i</span>
                    <span class="letter">n</span>
                    <span class="letter">g</span>
                    <span class="letter">&nbsp;</span>
                    <span class="letter">C</span>
                    <span class="letter">o</span>
                    <span class="letter">n</span>
                    <span class="letter">t</span>
                    <span class="letter">e</span>
                    <span class="letter">n</span>
                    <span class="letter">t</span>
                </h2>

                <p class="loading-subtext">
                    Comparing with academic sources
                </p>

                <div class="loading-status">
                    <div class="spinner"></div>
                    <span>Checking for matches</span>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Base Styles */
    .plagiarism-loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.96);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .loader-content {
        max-width: 40%;
        width: 100%;
        padding: 30px;
        text-align: center;
    }

    /* Document Animation */
    .document-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
    }

    .document-icon {
        width: 100%;
        height: 100%;
        color: #e0e0e0;
        stroke-width: 1.5;
    }

    .scanning-beam {
        position: absolute;
        top: 0;
        left: 50%;
        width: 3px;
        height: 100%;
        background-color: #4285f4;
        transform: translateX(-50%);
        animation: scan 2s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        box-shadow: 0 0 10px rgba(66, 133, 244, 0.4);
    }

    .scanning-beam::before,
    .scanning-beam::after {
        content: '';
        position: absolute;
        left: 0;
        width: 100%;
        height: 20px;
        background: linear-gradient(to bottom,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%);
    }

    .scanning-beam::after {
        bottom: 0;
        background: linear-gradient(to top,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%);
    }

    .search-icon {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 30px;
        height: 30px;
        color: #4285f4;
        animation: float 3s ease-in-out infinite;
    }

    /* Progress Bar */
    .progress-container {
        margin-bottom: 30px;
    }

    .progress-labels {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #70757a;
        margin-bottom: 8px;
    }

    .progress-bar {
        height: 6px;
        background-color: #f1f3f4;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, #4285f4, #34a853);
        border-radius: 3px;
        animation: progress 15s ease-out forwards;
    }

    /* Text Styles */
    .loading-text {
        color: #3c4043;
    }

    .loading-heading {
        font-size: 22px;
        font-weight: 500;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .loading-subtext {
        font-size: 15px;
        margin-bottom: 20px;
        color: #5f6368;
    }

    .highlight {
        color: #4285f4;
        font-weight: 500;
    }

    .loading-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 14px;
        color: #70757a;
    }

    .spinner {
        width: 16px;
        height: 16px;
        border: 3px solid rgba(66, 133, 244, 0.2);
        border-top-color: #4285f4;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Error Message Styles */
    .error-message {
        background: linear-gradient(90deg, #fdecea 0%, #fff6f4 100%);
        color: #d93025;
        border: 1px solid #fbcfcf;
        border-radius: 6px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 15px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(217, 48, 37, 0.06);
        animation: fadeInError 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .error-icon {
        font-size: 18px;
        margin-right: 6px;
        color: #d93025;
        flex-shrink: 0;
        filter: drop-shadow(0 1px 2px rgba(217, 48, 37, 0.10));
    }

    @keyframes fadeInError {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Letter Animations */
    .letter {
        display: inline-block;
        animation: bounce 1.2s ease infinite;
    }

    /* Individual letter delays */
    .letter:nth-child(1) {
        animation-delay: 0.1s;
    }

    .letter:nth-child(2) {
        animation-delay: 0.2s;
    }

    .letter:nth-child(3) {
        animation-delay: 0.3s;
    }

    .letter:nth-child(4) {
        animation-delay: 0.4s;
    }

    .letter:nth-child(5) {
        animation-delay: 0.5s;
    }

    .letter:nth-child(6) {
        animation-delay: 0.6s;
    }

    .letter:nth-child(7) {
        animation-delay: 0.7s;
    }

    .letter:nth-child(8) {
        animation-delay: 0.8s;
    }

    .letter:nth-child(9) {
        animation-delay: 0.9s;
    }

    .letter:nth-child(10) {
        animation-delay: 1.0s;
    }

    .letter:nth-child(11) {
        animation-delay: 1.1s;
    }

    .letter:nth-child(12) {
        animation-delay: 1.2s;
    }

    .letter:nth-child(13) {
        animation-delay: 1.3s;
    }

    .letter:nth-child(14) {
        animation-delay: 1.4s;
    }

    .letter:nth-child(15) {
        animation-delay: 1.5s;
    }

    .letter:nth-child(16) {
        animation-delay: 1.6s;
    }

    /* Animations */
    @keyframes scan {

        0%,
        100% {
            transform: translateY(0) translateX(-50%);
        }

        50% {
            transform: translateY(15px) translateX(-50%);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-6px);
        }
    }

    @keyframes progress {
        0% {
            width: 0;
        }

        100% {
            width: 100%;
        }
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }
</style>

<script>
    // Animate the percentage counter
    document.addEventListener('DOMContentLoaded', function() {
        const counter = document.querySelector('.progress-counter');
        let current = 0;
        const target = 100;
        const duration = 15000; // Match CSS animation duration

        const animateCounter = () => {
            const increment = target / (duration / 16);
            current += increment;

            if (current < target) {
                counter.textContent = Math.floor(current) + '%';
                requestAnimationFrame(animateCounter);
            } else {
                counter.textContent = target + '%';
            }
        };

        animateCounter();
    });
</script>
