<?php

namespace App\Filament\Resources\PembayaranBalkenResource\Pages;

use App\Filament\Resources\PembayaranBalkenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Cost;
use App\Models\PembayaranBalken;

class ListPembayaranBalkens extends ListRecords
{
    protected static string $resource = PembayaranBalkenResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

public function mount(): void
{
    parent::mount();

    $costs = Cost::all()->keyBy('grade');
    PembayaranBalken::generateFromKubikasi($costs);
}
}
