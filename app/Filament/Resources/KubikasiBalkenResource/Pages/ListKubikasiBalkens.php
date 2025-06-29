<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\KubikasiBalkenOverview;

class ListKubikasiBalkens extends ListRecords
{
    protected static string $resource = KubikasiBalkenResource::class;

      protected function getHeaderWidgets(): array
    {
        return [
            KubikasiBalkenOverview::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
