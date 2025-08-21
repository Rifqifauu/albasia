<?php

namespace App\Filament\Resources\PembayaranLogResource\Pages;

use App\Filament\Resources\PembayaranLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembayaranLog extends EditRecord
{
    protected static string $resource = PembayaranLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
