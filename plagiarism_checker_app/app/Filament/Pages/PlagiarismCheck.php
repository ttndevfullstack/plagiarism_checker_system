<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PlagiarismCheck extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Plagiarism Check';

    protected static ?string $title = 'Plagiarism Check Page';

    protected static string $view = 'filament.pages.plagiarism-check';

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getViewData(): array
    {
        $json = '{
            "status": "success",
            "data": {
              "total_similarity_percentage": 42.7,
              "overall_verdict": "Moderate plagiarism detected. This content has significant similarities with other sources.",
              "source_count": 5,
              "processed_at": "2023-11-15T14:30:45Z",
              "paragraphs": [
                {
                  "id": "para-1",
                  "text": "The quick brown fox jumps over the lazy dog.",
                  "similarity_percentage": 92.5,
                  "sources": [
                    {
                      "url": "https://example.com/source1",
                      "title": "Common English Phrases",
                      "similarity_percentage": 92.5,
                      "published_date": "2020-05-12"
                    }
                  ]
                },
                {
                  "id": "para-2",
                  "text": "Artificial intelligence is transforming modern industries.",
                  "similarity_percentage": 15.2,
                  "sources": []
                },
                {
                  "id": "para-3",
                  "text": "Climate change represents the greatest challenge of our time.",
                  "similarity_percentage": 67.8,
                  "sources": [
                    {
                      "url": "https://example.org/climate-report",
                      "title": "Global Climate Assessment 2023",
                      "similarity_percentage": 45.2,
                      "published_date": "2023-02-18"
                    },
                    {
                      "url": "https://news.example/environment",
                      "title": "Environmental Challenges Today",
                      "similarity_percentage": 22.6,
                      "published_date": "2022-11-05"
                    }
                  ]
                }
              ],
              "sources_summary": [
                {
                  "url": "https://example.com/source1",
                  "title": "Common English Phrases",
                  "total_matched": 1,
                  "highest_similarity": 92.5
                },
                {
                  "url": "https://example.org/climate-report",
                  "title": "Global Climate Assessment 2023",
                  "total_matched": 1,
                  "highest_similarity": 45.2
                },
                {
                  "url": "https://news.example/environment",
                  "title": "Environmental Challenges Today",
                  "total_matched": 1,
                  "highest_similarity": 22.6
                }
              ]
            }
          }';

        return [
            'results' => json_decode($json, true)['data'],
            'original_text' => 'The quick brown fox jumps over the lazy dog. Artificial intelligence is transforming modern industries. Climate change represents the greatest challenge of our time.',
        ];
    }
}
