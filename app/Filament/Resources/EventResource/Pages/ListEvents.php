<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\CustomActions;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CustomActions\CalendarAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
