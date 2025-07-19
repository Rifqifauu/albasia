<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use App\Models\Cost;
use App\Models\Pallets;
use App\Models\KubikasiBalken;

use Filament\Resources\Pages\Page;

class ViewKubikasiBalken extends Page
{
    protected static string $resource = KubikasiBalkenResource::class;
    protected static string $view = 'filament.resources.kubikasi-balken-resource.pages.view-kubikasi-balken';

    public string $tanggal;
    public string $nomor_polisi;
    public $pallets;
    public int $total_jumlah = 0;
    public float $total_volume = 0;
    public $cost;
    public $total_tagihan;

public function mount(): void
{
    $this->tanggal = request()->route('tanggal');
    $this->nomor_polisi = request()->route('nomor_polisi');

    $this->cost = Cost::where('tipe', 'balken')
        ->get()
        ->keyBy(fn($item) => strtoupper($item->grade));

    $this->pallets = KubikasiBalken::rekapPerGrade($this->nomor_polisi, $this->tanggal);

    $this->total_tagihan = KubikasiBalken::hitungTotalTagihan(
        $this->nomor_polisi,
        $this->tanggal,
        $this->cost
    );

    $this->total_jumlah = $this->pallets->sum('total_jumlah');
    $this->total_volume = $this->pallets->sum('total_volume');
}

public function getDetailPalletsProperty()
{
    return KubikasiBalken::detailpallet_balken($this->nomor_polisi, $this->tanggal);
}

    public static function getRoute(): string
    {
        return '/{tanggal}/{nomor_polisi}';
    }
}
