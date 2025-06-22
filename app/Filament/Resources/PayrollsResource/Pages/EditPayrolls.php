<?php

namespace App\Filament\Resources\PayrollsResource\Pages;

use App\Filament\Resources\PayrollsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayrolls extends EditRecord
{
    protected static string $resource = PayrollsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
