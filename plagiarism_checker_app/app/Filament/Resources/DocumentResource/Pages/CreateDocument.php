<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\ProcessDocumentUpload;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['media_id']) {
            $media = \Awcodes\Curator\Models\Media::find($data['media_id']);
            if ($media) {
                $data['original_name'] = $media->name . '.' . $media->ext;
            }
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        ProcessDocumentUpload::dispatch($this->record);
    }
}
