<?php

namespace App\Filament\Resources\KubikasiBalkenResource\Pages;

use App\Filament\Resources\KubikasiBalkenResource;
use App\Models\Tallies;
use Filament\Resources\Pages\Page;
use App\Models\Pallets;
use Illuminate\Support\Collection;

class ViewKubikasiBalken extends Page
{
    protected static string $resource = KubikasiBalkenResource::class;

    protected static string $view = 'filament.resources.kubikasi-balken-resource.pages.view-kubikasi-balken';

    public $tanggal;
    public $nomor_polisi;
    public $pallets;
    public $total_jumlah = 0;
    public $total_volume = 0;

    public function mount(): void
    {
        $this->tanggal = request()->route('tanggal');
        $this->nomor_polisi = request()->route('nomor_polisi');

        $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];

        // Debugging - log parameters
        \Log::info('Parameters:', [
            'tanggal' => $this->tanggal,
            'nomor_polisi' => $this->nomor_polisi
        ]);

        // Query dengan JOIN yang sama persis seperti SQL yang berhasil
        $rawData = Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
            ->selectRaw('pallets.grade, SUM(pallets.jumlah) as total_jumlah, SUM(pallets.volume) as total_volume')
            ->where('tallies.nomor_polisi', $this->nomor_polisi)
            ->whereDate('tallies.created_at', $this->tanggal)
            ->groupBy('pallets.grade')
            ->get()
            ->keyBy('grade');

        // Debugging - log query result
        \Log::info('Raw Data Result:', $rawData->toArray());

        // Jika tidak ada hasil, lakukan debugging lebih lanjut
        if ($rawData->isEmpty()) {
            // Cek apakah ada data tallies dengan parameter tersebut
            $talliesExist = Tallies::where('nomor_polisi', $this->nomor_polisi)
                ->whereDate('created_at', $this->tanggal)
                ->exists();
            
            \Log::info('Tallies exists:', ['exists' => $talliesExist]);

            // Cek apakah ada pallets yang terkait
            $palletsCount = Pallets::whereHas('tally', function ($query) {
                $query->where('nomor_polisi', $this->nomor_polisi)
                      ->whereDate('created_at', $this->tanggal);
            })->count();
            
            \Log::info('Related pallets count:', ['count' => $palletsCount]);

            // Ambil sample data untuk debugging
            $sampleTallies = Tallies::where('nomor_polisi', $this->nomor_polisi)
                ->whereDate('created_at', $this->tanggal)
                ->with('pallets')
                ->first();
            
            if ($sampleTallies) {
                \Log::info('Sample Tallies Data:', [
                    'id' => $sampleTallies->id,
                    'nomor_polisi' => $sampleTallies->nomor_polisi,
                    'created_at' => $sampleTallies->created_at,
                    'pallets_count' => $sampleTallies->pallets->count(),
                    'pallets_data' => $sampleTallies->pallets->toArray()
                ]);
            }
        }

        // Mapping data sesuai dengan struktur yang benar
        $this->pallets = collect($grades)->map(function ($grade) use ($rawData) {
$data = $rawData->get(strtolower($grade));
            return (object) [
                'grade' => $grade,
                'total_jumlah' => $data ? $data->total_jumlah : 0,
                'total_volume' => $data ? $data->total_volume : 0,
            ];
        });

        $this->total_jumlah = $this->pallets->sum('total_jumlah');
        $this->total_volume = $this->pallets->sum('total_volume');

        // Log hasil akhir
        \Log::info('Final Result:', [
            'total_jumlah' => $this->total_jumlah,
            'total_volume' => $this->total_volume,
            'pallets_data' => $this->pallets->toArray()
        ]);
    }

    public static function getRoute(): string
    {
        return '/{tanggal}/{nomor_polisi}';
    }
}