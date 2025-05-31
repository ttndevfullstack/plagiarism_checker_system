<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PlagiarismService
{
    public function checkPlagiarism(array $content): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker', [
            'content' => $this->removeHTMLTag($content),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Plagiarism check failed: ' . $response->body());
        }

        return $response->json();
    }

    public function checkPDFPlagiarism(string $filePath): array
    {
        $response = Http::attach(
            'file', 
            file_get_contents($filePath), 
            basename($filePath)
        )->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker/pdf');

        if (!$response->successful()) {
            throw new \Exception('PDF plagiarism check failed: ' . $response->body());
        }
        
        $responseData = $response->json();
        // dd($responseData);
        
        if (!isset($responseData['data'])) {
            throw new \Exception('Invalid response format from plagiarism service');
        }

        return [
            'file_path' => $responseData['data']['file_path'] ?? null,
            'results' => $responseData['data']['results'] ?? [],
            'status' => $responseData['success'] ?? false,
            'message' => $responseData['message'] ?? ''
        ];
    }

    private function removeHTMLTag(array $paragraphs): array
    {
        return array_map(fn ($paragraph) => strip_tags($paragraph), $paragraphs);
    }
}
