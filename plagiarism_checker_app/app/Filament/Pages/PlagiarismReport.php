<?php

namespace App\Filament\Pages;

use App\Models\PlagiarismHistory;
use App\Services\PlagiarismService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PlagiarismReport extends Page
{
    protected static string $layout = 'filament-panels::components.layout.without-nav';

    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationLabel = 'Plagiarism Report';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $title = 'Plagiarism Report';

    protected static string $view = 'filament.pages.pdf-plagiarism-report';

    protected static bool $shouldRegisterNavigation = false;

    public $data = null;

    public ?array $results = null;

    public ?string $error = null;

    public bool $isLoading = true;

    public string $fileName = '';

    public string $filePath = '';

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
        $this->isLoading = true;

        if (request()->has('history_id')) {
            $history = PlagiarismHistory::find(request()->get('history_id'));
            $this->filePath = app(PlagiarismService::class)->decodeAndSaveFile($history->encoded_file);
            $this->results = $history->results['data'];
        } else if (request()->has('data')) {
            $data = json_decode(base64_decode(request()->get('data')), true);

            try {
                $response = app(PlagiarismService::class)->checkPlagiarismByFile($data['file_path']);

                $this->filePath = $response['file_path'];
                $this->results = $response['results']['data'];

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
        }
        $this->isLoading = false;
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
