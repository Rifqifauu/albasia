<?php

namespace App\Filament\Resources\TallyLogResource\Pages;

use App\Filament\Resources\TallyLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTallyLog extends EditRecord
{
    protected static string $resource = TallyLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
