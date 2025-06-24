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
        $response = Http::timeout(1800)->attach(
            'file', 
            file_get_contents($filePath), 
            basename($filePath)
        )->post(config('plagiarism-checker.flask_app_url') . '/v1/api/plagiarism-checker/file');

        if (!$response->successful()) {
            throw new \Exception('PDF plagiarism check failed: ' . $response->body());
        }
        
        $responseData = $response->json();
        
        if (!isset($responseData['data'])) {
            throw new \Exception('Invalid response format from plagiarism service');
        }

        // Decode and save the PDF file
        $pdfContent = base64_decode($responseData['data']['pdf_content']);
        $fileName = 'proofly_highlighted_' . time() . '.pdf';
        $filePath = 'public/downloads/' . $fileName;
        
        Storage::put($filePath, $pdfContent);

        return [
            'file_path' => asset('storage/downloads/' . $fileName),
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
