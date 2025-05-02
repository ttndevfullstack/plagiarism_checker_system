<?php

namespace App\Filament\Pages;

use App\Services\PlagiarismService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class PlagiarismCheck extends Page
{
    protected static ?string $navigationIcon = 'heroicon-c-shield-check';

    protected static ?string $navigationLabel = 'Plagiarism Check';

    protected static ?string $navigationGroup = 'Plagiarism Management';

    protected static ?string $title = 'Plagiarism Check Page';

    protected static string $view = 'filament.pages.plagiarism-check';

    public $preview_content = null;

    public $results = null;

    public $isLoading = true;

    public $error = null;

    public static function canAccess(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Check Plagiarism')
                ->url(\App\Filament\Resources\PlagiarismCheckerResource::getUrl('create')),
        ];
    }

    public function mount()
    {
        if (request()->has('preview_content')) {
            $this->preview_content = json_decode(base64_decode(request()->get('preview_content')), true);
            
            try {
                $response = app(PlagiarismService::class)->checkPlagiarism($this->preview_content['content']);
                $this->results = $response['data'];
                $this->isLoading = false;
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                $this->isLoading = false;
                Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->send();
            }
        }
    }

    protected function getViewData(): array
    {
        return [
            'preview_content' => $this->preview_content,
            'results' => $this->results,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
        ];
    }
}
