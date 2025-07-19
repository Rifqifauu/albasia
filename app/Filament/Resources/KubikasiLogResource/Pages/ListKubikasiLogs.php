<?php

namespace App\Filament\Resources\KubikasiLogResource\Pages;

use App\Filament\Resources\KubikasiLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKubikasiLogs extends ListRecords
{
    protected static string $resource = KubikasiLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
