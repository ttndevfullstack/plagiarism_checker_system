<?php

namespace App\Services;

use App\Models\PlagiarismHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PlagiarismService
{
    public function checkPlagiarismByText(array $text): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker', [
            'content' => $this->removeHTMLTag($text),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Plagiarism check failed: ' . $response->body());
        }

        return $response->json();
    }

    public function checkPlagiarismByFile(string $filePath): array
    {
        $response = Http::timeout(1800)->attach(
            'file', 
            file_get_contents($filePath), 
            basename($filePath)
        )->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker/file');

        if (!$response->successful()) {
            throw new \Exception('PDF plagiarism check failed: ' . $response->body());
        }
        
        $responseData = $response->json();
        
        if (! isset($responseData['data'])) {
            throw new \Exception('Invalid response format from plagiarism service');
        }

        $filePath = $this->decodeAndSaveFile($responseData['data']['pdf_content']);

        return [
            'encoded_file' => $responseData['data']['pdf_content'] ?? null,
            'file_path' => $filePath,
            'results' => $responseData['data']['results'] ?? [],
            'status' => $responseData['success'] ?? false,
            'message' => $responseData['message'] ?? ''
        ];
    }

    public function decodeAndSaveFile(string $encoded_file): string
    {
        $pdfContent = base64_decode($encoded_file);
        $fileName = 'proofly_highlighted_' . time() . '.pdf';
        $filePath = 'public/downloads/' . $fileName;
        
        Storage::put($filePath, $pdfContent);
        return asset('storage/downloads/' . $fileName);
    }

    private function removeHTMLTag(array $paragraphs): array
    {
        return array_map(fn ($paragraph) => strip_tags($paragraph), $paragraphs);
    }
}
