<?php
 
namespace App\Filament\Pages;
 
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
 
class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Üdvözlünk oldalunkon!';
    protected static ?string $navigationIcon = 'tabler-home-heart';
}