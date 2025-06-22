<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKubikasiBalkens extends ListRecords
{
    protected static string $resource = KubikasiBalkenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
