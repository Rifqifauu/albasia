<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TallyBalken;
use App\Models\TallyLog;
class BahanBaku extends BaseWidget
{
    protected function getStats(): array
    {
                    // Total Stok (is_stock = true)
$stokLogCount = TallyLog::where('is_stock', true)->sum('total_log');
// Total Diproduksi (is_stock = false)
$producedLogCount = TallyLog::where('is_stock', false)->sum('total_log');
                    // Total Stok (is_stock = true)
$stokBalkenCount = TallyBalken::where('is_stock', true)->sum('total_balken');
// Total Diproduksi (is_stock = false)
$producedBalkenCount = TallyBalken::where('is_stock', false)->sum('total_balken');

        return [

            Stat::make('Total Stok Log', number_format($stokLogCount) . ' pcs')
                ->description('Log yang masih dalam stok')
                ->icon('heroicon-o-cube')
                ->color('info'),

            Stat::make('Log Telah Diproduksi', number_format($producedLogCount) . ' pcs')
                ->description('Log yang sudah diproses')
                ->icon('heroicon-o-check-badge')
                ->color('gray'),
            Stat::make('Total Stok Balken', number_format($stokBalkenCount) . ' pcs')
                ->description('Balken yang masih dalam stok')
                ->icon('heroicon-o-cube')
                ->color('info'),

            Stat::make('Balken Telah Diproduksi', number_format($producedBalkenCount) . ' pcs')
                ->description('Balken yang sudah diproses')
                ->icon('heroicon-o-check-badge')
                ->color('gray'),
        ];
    }
}
