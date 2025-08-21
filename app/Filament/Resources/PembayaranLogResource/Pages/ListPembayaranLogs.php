<?php

namespace App\Filament\Resources\PembayaranLogResource\Pages;

use App\Filament\Resources\PembayaranLogResource;
use App\Models\PembayaranLog;
use App\Models\Cost;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembayaranLogs extends ListRecords
{
    protected static string $resource = PembayaranLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
    public function mount(): void
{
    parent::mount();

    $costs = Cost::all()->keyBy('grade');
    PembayaranLog::generateFromKubikasi($costs);
}
}
