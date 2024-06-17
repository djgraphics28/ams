<?php


namespace App\Filament\Pages;

use Filament\Facades\Filament;

class Dashboard extends \Filament\Pages\Dashboard
{
    // protected static ?string $navigationIcon = 'heroicon-o-home';

    // protected static string $view = 'filament::pages.dashboard';

    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }
}
