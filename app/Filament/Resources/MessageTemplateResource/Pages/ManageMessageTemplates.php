<?php

namespace App\Filament\Resources\MessageTemplateResource\Pages;

use App\Filament\Resources\MessageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMessageTemplates extends ManageRecords
{
    protected static string $resource = MessageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
