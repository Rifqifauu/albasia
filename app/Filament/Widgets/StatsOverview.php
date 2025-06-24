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
        $tallies = Tallies::whereDate('created_at', Carbon::today())->count();
        return [
            Stat::make('Total Balken Hari Ini', $balken)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Tally Hari Ini', $tallies)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
