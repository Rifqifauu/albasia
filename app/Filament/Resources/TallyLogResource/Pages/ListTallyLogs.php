<?php

namespace App\Filament\Resources\TallyLogResource\Pages;

use App\Filament\Resources\TallyLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTallyLogs extends ListRecords
{
    protected static string $resource = TallyLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
