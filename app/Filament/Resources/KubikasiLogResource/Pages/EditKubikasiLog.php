<?php

namespace App\Filament\Resources\KubikasiLogResource\Pages;

use App\Filament\Resources\KubikasiLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKubikasiLog extends EditRecord
{
    protected static string $resource = KubikasiLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
