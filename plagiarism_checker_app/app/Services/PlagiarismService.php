<?php

namespace App\Services;

use App\Models\PlagiarismCheck;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PlagiarismService
{
    public function checkPlagiarism($document = null, $content = null): PlagiarismCheck
    {
        $tempFile = null;
        
        try {
            if ($content) {
                // Save content to temporary file
                $tempFile = tempnam(sys_get_temp_dir(), 'plagiarism_') . '.txt';
                file_put_contents($tempFile, $content);
                $filePath = $tempFile;
            } else {
                $filePath = Storage::path($document->path);
            }

            $response = Http::attach(
                'files',
                fopen($filePath, 'r'),
                $document ? $document->name : 'content.txt'
            )->post(env('FLASK_APP_URL') . '/v1/api/plagiarism-checker');

            if (!$response->successful()) {
                throw new \Exception('Plagiarism check failed: ' . $response->body());
            }

            $results = $response->json();
            
            return PlagiarismCheck::create([
                'user_id' => auth()->id(),
                'document_id' => $document?->id,
                'text_content' => $content,
                'similarity_score' => $this->calculateOverallScore($results['data']['matches']),
                'confidence_score' => $this->calculateConfidenceScore($results['data']['matches']),
                'matches' => $results['data']['matches'],
                'metadata' => [
                    'check_date' => now(),
                    'source_count' => count($results['data']['matches'])
                ]
            ]);

        } finally {
            if ($tempFile && file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    private function calculateOverallScore(array $matches): float 
    {
        if (empty($matches)) {
            return 0;
        }

        $totalScore = 0;
        foreach ($matches as $match) {
            $totalScore += $match['similarity_score'];
        }

        return $totalScore / count($matches);
    }

    private function calculateConfidenceScore(array $matches): int
    {
        $score = $this->calculateOverallScore($matches);
        
        if ($score >= 90) return 5;
        if ($score >= 80) return 4;
        if ($score >= 70) return 3;
        if ($score >= 50) return 2;
        return 1;
    }
}
