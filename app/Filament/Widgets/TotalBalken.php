<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pallets;
use Illuminate\Support\Facades\DB;

class TotalBalken extends ChartWidget
{
    protected static ?string $heading = 'Total Balken per Bulan';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $year = now()->year;

        $data = Pallets::selectRaw('MONTH(created_at) as month, SUM(jumlah) as total')
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Inisialisasi semua bulan
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }
        $log = [];
        for ($i = 1; $i <= 12; $i++) {
            $log[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Balken',
                    'data' => $result,
                   
                ],
              
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

  public function getColumnSpan(): int|string|array
{
    return 2;
}



    protected function getType(): string
    {
        return 'line'; // bisa diganti ke 'bar' kalau kamu mau
    }
}
