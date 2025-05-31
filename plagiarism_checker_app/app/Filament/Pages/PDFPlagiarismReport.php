<?php

namespace App\Filament\Pages;

use App\Services\PlagiarismService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PDFPlagiarismReport extends Page
{
    protected static string $layout = 'filament-panels::components.layout.without-nav';

    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationLabel = 'PDF Plagiarism Report';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $title = 'Plagiarism Check Page';

    protected static string $view = 'filament.pages.pdf-plagiarism-report';

    protected static bool $shouldRegisterNavigation = true;

    public $data = null;

    public ?array $results = null;

    public $isLoading = true;

    public $error = null;

    public bool $giveMeFile = false;

    public string $fileName = '';

    public string $filePath = '';

    public string $fileType = '';

    public ?string $outputPath = null;

    public ?string $outputFileName = null;

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Continue Check')
                ->url(\App\Filament\Resources\PlagiarismCheckerResource::getUrl('create')),
        ];
    }

    public function mount()
    {
        $this->isLoading = false;

        if (request()->has('data')) {
            $data = json_decode(base64_decode(request()->get('data')), true);

            try {
                $results = app(PlagiarismService::class)->checkPDFPlagiarism($data['file_path']);

                // Store the file path relative to public directory
                $this->filePath = asset('storage/downloads/' . basename($data['file_path']));
                $this->results = $results['results'];

                Notification::make()
                    ->success()
                    ->title('Analysis Complete')
                    ->body('The PDF has been analyzed for plagiarism.')
                    ->send();
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->send();
            }

            $this->fileName = $data['filename'] ?? '';
            $this->isLoading = false;
        }
    }

    protected function getViewData(): array
    {
        return [
            'filePath' => $this->filePath,
            'results' => $this->results,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
        ];
    }
}
