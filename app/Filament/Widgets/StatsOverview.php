<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Tallies;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $balken = Tallies::whereDate('created_at', Carbon::today())->sum('total_balken');
        $volume = Tallies::whereDate('created_at', Carbon::today())->sum('total_volume');
        $tallies = Tallies::whereDate('created_at', Carbon::today())->count();
        return [
           Stat::make('Total Balken Hari Ini', number_format($balken) . ' pcs')
    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Volume Hari Ini', number_format($volume, 2, ',', '.') . ' cm³')
    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Tally Hari Ini', $tallies)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
