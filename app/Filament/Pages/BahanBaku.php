<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\KubikasiLog;
use App\Models\KubikasiBalken;

class BahanBaku extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.bahan-baku';

    public ?\Illuminate\Support\Collection $logStok = null;
    public ?\Illuminate\Support\Collection $logProduksi = null;
    public ?\Illuminate\Support\Collection $balkenStok = null;
    public ?\Illuminate\Support\Collection $balkenProduksi = null;

  public function mount()
{
    $logData = KubikasiLog::rekapStokDanProduksi();
    $balkenData = KubikasiBalken::rekapStokDanProduksi();

    $this->logStok = $logData->where('is_stock', true);
    $this->logProduksi = $logData->where('is_stock', false);

    $this->balkenStok = $balkenData->where('is_stock', true);
    $this->balkenProduksi = $balkenData->where('is_stock', false);
}

protected function getViewData(): array
{
    return [
        'logStok' => $this->logStok,
        'logProduksi' => $this->logProduksi,
        'balkenStok' => $this->balkenStok,
        'balkenProduksi' => $this->balkenProduksi,
    ];
}
protected function getHeaderWidgets(): array
{
    return [
        \App\Filament\Resources\AdminResource\Widgets\BahanBaku::class
    ];
}

}
