<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PalletBalken;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TotalBalken extends ChartWidget
{
    protected static ?string $heading = 'Total Balken';
    protected static ?int $sort = 1;

    protected function getFilters(): ?array
    {
       return [
    'bulanan' => 'Per Bulan (12 Bulan Terakhir)',
    'harian' => 'Per Hari (7 Hari Terakhir)',
];

    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'bulanan';

        switch ($filter) {
            case 'harian':
                $startDate = Carbon::today()->subDays(6);
                $data = PalletBalken::selectRaw('DATE(created_at) as label, SUM(jumlah) as total')
                    ->whereDate('created_at', '>=', $startDate)
                    ->groupBy('label')
                    ->orderBy('label')
                    ->pluck('total', 'label')
                    ->toArray();

                $labels = [];
                $values = [];
                for ($i = 0; $i < 7; $i++) {
                    $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                    $labels[] = Carbon::parse($date)->translatedFormat('d M');
                    $values[] = $data[$date] ?? 0;
                }
                break;
            default: // bulanan
                $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
                $data = PalletBalken::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(jumlah) as total')
                    ->whereDate('created_at', '>=', $startMonth)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('total', 'month')
                    ->toArray();

                $labels = [];
                $values = [];
                for ($i = 0; $i < 12; $i++) {
                    $month = $startMonth->copy()->addMonths($i)->format('Y-m');
                    $labels[] = Carbon::parse($month . '-01')->translatedFormat('M Y');
                    $values[] = $data[$month] ?? 0;
                }
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Balken',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 1;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
