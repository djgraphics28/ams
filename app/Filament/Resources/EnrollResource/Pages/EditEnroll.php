<?php

namespace App\Filament\Resources\EnrollResource\Pages;

use App\Filament\Resources\EnrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnroll extends EditRecord
{
    protected static string $resource = EnrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
