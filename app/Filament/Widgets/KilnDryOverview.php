<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TallyBalken;
use App\Models\TallyLog;
use Illuminate\Support\Facades\Log;

class KilnDryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            // Get the number of Balken burned today based on the related KilnDry model
            $balkenHariIni = TallyBalken::whereHas('kilndry', function ($query) {
                $query->whereDate('tanggal_bakar', today());
            })->count();
            $logHariIni = TallyLog::whereHas('kilndry', function ($query) {
                $query->whereDate('tanggal_bakar', today());
            })->count();

            return [
                Stat::make('Total Balken Hari Ini', $balkenHariIni)
                    ->description('Balken yang dibakar hari ini')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
                Stat::make('Total Log Hari Ini', $logHariIni)
                    ->description('Log yang dibakar hari ini')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
            ];
        } catch (\Exception $e) {
            Log::error('Error in KilnDryOverview widget: ' . $e->getMessage());

            return [
                Stat::make('Total Balken Hari Ini', 0)
                    ->description('Error loading data')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
            ];
        }
    }
}
