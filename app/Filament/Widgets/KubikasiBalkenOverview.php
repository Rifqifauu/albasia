<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\KubikasiBalken;
use Carbon\Carbon;

class KubikasiBalkenOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tagihanHariIni = KubikasiBalken::queryWithTotalTagihan()
            ->whereDate('tallies.created_at', Carbon::today())
            ->pluck('total_tagihan')->sum();
        return [
            Stat::make('Total Tagihan Hari Ini', 'Rp ' . number_format($tagihanHariIni))
                ->description('Tagihan yang tercatat hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
