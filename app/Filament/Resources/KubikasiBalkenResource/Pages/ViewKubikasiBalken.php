<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use App\Models\Cost;
use App\Models\Pallets;
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
    ->keyBy(function ($item) {
        return strtoupper($item->grade); // supaya cocok dengan $detail->grade
    });
        $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];

        // Rekap per grade (tanpa pagination)
        $rekapPerGrade = Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
            ->selectRaw('
                LOWER(pallets.grade) as grade_key,
                pallets.grade,
                SUM(pallets.jumlah) as total_jumlah,
                SUM(pallets.volume) as total_volume
            ')
            ->where('tallies.nomor_polisi', $this->nomor_polisi)
            ->whereDate('tallies.created_at', $this->tanggal)
            ->groupBy('pallets.grade')
            ->get()
            ->keyBy('grade_key');

        // Map ke struktur blade
        $this->pallets = collect($grades)->map(function ($grade) use ($rekapPerGrade) {
            $data = $rekapPerGrade->get(strtolower($grade));
            return (object) [
                'grade' => strtoupper($grade),
                'total_jumlah' => $data?->total_jumlah ?? 0,
                'total_volume' => $data?->total_volume /1000000 ?? 0,
            ];
        });
$this->total_tagihan = Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
    ->selectRaw('
        pallets.grade,
        SUM(pallets.volume) as total_volume
    ')
    ->where('tallies.nomor_polisi', $this->nomor_polisi)
    ->whereDate('tallies.created_at', $this->tanggal)
    ->groupBy('pallets.grade')
    ->get()
    ->sum(function ($item) {
        $grade = strtoupper($item->grade);
        $harga = $this->cost[$grade]->harga ?? 0;
        return $item->total_volume /1000000  * $harga;
    });

        $this->total_jumlah = $this->pallets->sum('total_jumlah');
        $this->total_volume = $this->pallets->sum('total_volume') ;
    }

    // 👇 Gunakan accessor Livewire untuk data yang kompleks dan ingin paginasi
    public function getDetailPalletsProperty()
    {
        return Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
            ->selectRaw('
                pallets.grade,
                pallets.tebal,
                pallets.lebar,
                pallets.panjang,
                SUM(pallets.jumlah) as total_jumlah,
                SUM(pallets.volume) /1000000 as total_volume
            ')
            ->where('tallies.nomor_polisi', $this->nomor_polisi)
            ->whereDate('tallies.created_at', $this->tanggal)
            ->groupBy('pallets.grade', 'pallets.tebal', 'pallets.lebar', 'pallets.panjang')
            ->orderBy('pallets.grade')
            ->paginate(5);
            
    }
    

    public static function getRoute(): string
    {
        return '/{tanggal}/{nomor_polisi}';
    }
}
