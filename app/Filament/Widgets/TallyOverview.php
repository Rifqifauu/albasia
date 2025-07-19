<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TallyBalken;
use Carbon\Carbon;

class TallyOverview extends BaseWidget
{
   protected function getStats(): array
{
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    // Helper function untuk menghitung stats comparison
   $calculateComparison = function($today, $yesterday, $unit = '') {
    // Dibulatkan untuk keperluan logika juga
    $roundedToday = round($today, 2);
    $roundedYesterday = round($yesterday, 2);

    if ($roundedYesterday == 0) {
        if ($roundedToday == 0) {
            return [
                'desc' => 'Tidak ada perubahan',
                'icon' => 'heroicon-m-minus',
                'color' => 'gray'
            ];
        } else {
            return [
                'desc' => 'Naik dari 0 ke ' . number_format($roundedToday, 2, ',', '.') . ' ' . $unit,
                'icon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success'
            ];
        }
    } else {
        $diff = $roundedToday - $roundedYesterday;
        $percent = round(($diff / $roundedYesterday) * 100);

        if ($diff == 0) {
            return [
                'desc' => 'Tidak ada perubahan dari kemarin',
                'icon' => 'heroicon-m-minus',
                'color' => 'gray'
            ];
        } else {
            return [
                'desc' => ($diff > 0 ? '+' : '') . $percent . '% dari kemarin',
                'icon' => $diff > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
                'color' => $diff > 0 ? 'success' : 'danger'
            ];
        }
    }
};



    // Balken
    $balkenToday = TallyBalken::whereDate('created_at', $today)->sum('total_balken');
    $balkenYesterday = TallyBalken::whereDate('created_at', $yesterday)->sum('total_balken');
    $balkenStats = $calculateComparison($balkenToday, $balkenYesterday, 'pcs');

    // Volume (dibagi langsung)
$volumeToday = TallyBalken::whereDate('created_at', $today)->sum('total_volume') / 1000000;
$volumeYesterday = TallyBalken::whereDate('created_at', $yesterday)->sum('total_volume') / 1000000;

// Gunakan hasil yang sudah dibagi untuk compare
$volumeStats = $calculateComparison($volumeToday, $volumeYesterday, 'm³');



    // TallyBalken
    $tallyToday = TallyBalken::whereDate('created_at', $today)->count();
    $tallyYesterday = TallyBalken::whereDate('created_at', $yesterday)->count();
    $tallyStats = $calculateComparison($tallyToday, $tallyYesterday, 'tally');

    return [
        Stat::make('Total Balken Hari Ini', number_format($balkenToday) . ' pcs')
            ->description($balkenStats['desc'])
            ->descriptionIcon($balkenStats['icon'])
            ->color($balkenStats['color']),

        Stat::make('Total Volume Hari Ini', number_format($volumeToday , 2, ',', '.') . ' m³')
            ->description($volumeStats['desc'])
            ->descriptionIcon($volumeStats['icon'])
            ->color($volumeStats['color']),

        Stat::make('Total Tally Hari Ini', $tallyToday)
            ->description($tallyStats['desc'])
            ->descriptionIcon($tallyStats['icon'])
            ->color($tallyStats['color']),
    ];
}
}
