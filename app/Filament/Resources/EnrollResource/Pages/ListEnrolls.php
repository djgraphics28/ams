<?php

namespace App\Filament\Resources\EnrollResource\Pages;

use App\Filament\Resources\EnrollResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnrolls extends ListRecords
{
    protected static string $resource = EnrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
