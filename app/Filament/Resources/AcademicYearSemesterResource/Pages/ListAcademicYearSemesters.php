<?php

namespace App\Filament\Resources\AcademicYearSemesterResource\Pages;

use App\Filament\Resources\AcademicYearSemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademicYearSemesters extends ListRecords
{
    protected static string $resource = AcademicYearSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
