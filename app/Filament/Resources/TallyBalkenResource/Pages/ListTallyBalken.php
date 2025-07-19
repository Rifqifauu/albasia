<?php

namespace App\Filament\Resources\TallyBalkenResource\Pages;

use App\Filament\Resources\TallyBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTallyBalken extends ListRecords
{
    protected static string $resource = TallyBalkenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
      protected function getHeaderWidgets(): array
    {
        return [
    \App\Filament\Widgets\TallyOverview::class,
];

    }
}
