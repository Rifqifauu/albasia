<?php

namespace App\Filament\Resources\KilnDryResource\Pages;

use App\Filament\Resources\DetailKilnDryRelationManagerResource\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\KilnDryResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Table as Tables;

class ViewKilnDry extends ViewRecord
{
    protected static string $resource = KilnDryResource::class;
     public function getRelationManagers(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }
     public function getHeaderActions(): array
{
    return [
        Action::make('Scan Barcode')
            ->url(fn () => KilnDryResource::getUrl('kilndry-scan', [
                'record' => $this->getRecord(),
            ]))
            ->icon('heroicon-o-qr-code'),
    ];
}

}
