<?php

namespace App\Filament\Resources\AcademicYearSemesterResource\Pages;

use App\Filament\Resources\AcademicYearSemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcademicYearSemester extends EditRecord
{
    protected static string $resource = AcademicYearSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
