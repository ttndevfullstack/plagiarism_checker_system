<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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

        // Save the highlighted PDF
        $publicPath = public_path('downloads');
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $outputFile = $publicPath . '/' . 'highlighted_' . basename($filePath);
        file_put_contents($outputFile, $response->body());

        return [
            'file_path' => asset('downloads/' . basename($outputFile))
        ];
    }

    private function removeHTMLTag(array $paragraphs): array
    {
        return array_map(fn ($paragraph) => strip_tags($paragraph), $paragraphs);
    }
}
