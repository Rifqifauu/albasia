<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKubikasiBalken extends EditRecord
{
    protected static string $resource = KubikasiBalkenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
