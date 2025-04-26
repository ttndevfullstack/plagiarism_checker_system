<?php

namespace App\Services;

use App\Models\PlagiarismCheck;
use Illuminate\Support\Facades\Http;

class PlagiarismService
{
    public function checkPlagiarism(?string $content = null): array
    {
        $response = Http::asForm()->post(env('FLASK_APP_URL') . '/v1/api/plagiarism-checker', [
            'content' => $content,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Plagiarism check failed: ' . $response->body());
        }

        return $response->json();
    }
}
