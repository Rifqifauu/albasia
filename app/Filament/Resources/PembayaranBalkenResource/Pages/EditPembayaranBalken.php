<?php

namespace App\Filament\Resources\PembayaranBalkenResource\Pages;

use App\Filament\Resources\PembayaranBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembayaranBalken extends EditRecord
{
    protected static string $resource = PembayaranBalkenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
