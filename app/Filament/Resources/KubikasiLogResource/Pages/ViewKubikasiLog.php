<?php

namespace App\Filament\Resources\KubikasiLogResource\Pages;

use App\Filament\Resources\KubikasiLogResource;
use App\Models\Cost;
use App\Models\Pallets;
use App\Models\KubikasiLog;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;

class ViewKubikasiLog extends Page
{
    protected static string $resource = KubikasiLogResource::class;
    protected static string $view = 'filament.resources.kubikasi-log-resource.pages.view-kubikasi-log';

    public string $tanggal;
    public string $nomor_polisi;
    public $pallets;
    public int $total_jumlah = 0;
    public float $total_volume = 0;
    public $cost;

    public function mount(): void
    {
        // Ambil parameter dari route
        $this->tanggal = request()->route('tanggal');
        $this->nomor_polisi = request()->route('nomor_polisi');

        // Validasi parameter
        if (empty($this->tanggal) || empty($this->nomor_polisi)) {
            abort(404, 'Parameter tanggal atau nomor polisi tidak valid');
        }

        // Load cost data
        $this->cost = Cost::where('tipe', 'log')
            ->get()
            ->keyBy(fn($item) => strtoupper($item->grade));

        // Load pallet data dengan error handling
        try {
            $this->pallets = KubikasiLog::rekapPerGrade($this->nomor_polisi, $this->tanggal);

            if ($this->pallets->isEmpty()) {
                session()->flash('warning', 'Data tidak ditemukan untuk nomor polisi dan tanggal yang dipilih.');
            }


            $this->total_jumlah = $this->pallets->sum('total_jumlah');
            $this->total_volume = $this->pallets->sum('total_volume');

        } catch (\Exception $e) {
            \Log::error('Error in ViewKubikasiLog mount: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());

            // Set default values
            $this->pallets = collect();
            $this->total_jumlah = 0;
            $this->total_volume = 0;
        }
    }

    public function getDetailPalletsProperty()
    {
        try {
            return KubikasiLog::detailpallet_log($this->nomor_polisi, $this->tanggal);
        } catch (\Exception $e) {
            \Log::error('Error in getDetailPalletsProperty: ' . $e->getMessage());
            return collect(); // Return empty collection jika error
        }
    }

    public static function getRoute(): string
    {
        return '/{tanggal}/{nomor_polisi}';
    }

    // Method untuk handle jika data kosong
    public function hasData(): bool
    {
        return $this->pallets && $this->pallets->isNotEmpty();
    }
}
