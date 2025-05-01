<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PlagiarismService
{
    public function checkPlagiarism(?string $content = null): array
    {
        $response = Http::asForm()->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker', [
            'content' => $content,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Plagiarism check failed: ' . $response->body());
        }

        return $response->json();
    }
}
