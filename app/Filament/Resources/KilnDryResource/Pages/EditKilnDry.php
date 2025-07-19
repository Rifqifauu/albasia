<?php

namespace App\Filament\Resources\KilnDryResource\Pages;

use App\Filament\Resources\KilnDryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKilnDry extends EditRecord
{
    protected static string $resource = KilnDryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
