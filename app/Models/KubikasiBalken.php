<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class KubikasiBalken extends Tallies
{
     protected $table = 'tallies'; 

     public static function queryWithTotalTagihan(): Builder
{
    return static::query()
        ->leftJoin('pallets', 'tallies.id', '=', 'pallets.tally_id')
        ->leftJoinSub(
            \DB::table('costs')
                ->selectRaw('grade, harga')
                ->where('tipe', 'balken'),
            'filtered_costs',
            fn($join) => $join->on(\DB::raw('UPPER(pallets.grade)'), '=', 'filtered_costs.grade')
        )
        ->selectRaw('
            MIN(tallies.id) as id,
            tallies.nomor_polisi, 
            DATE(tallies.created_at) as created_at, 
            COALESCE(SUM(pallets.volume /1000000 * filtered_costs.harga), 0) as total_tagihan
        ')
        ->groupByRaw('tallies.nomor_polisi, DATE(tallies.created_at)')
        ->orderByRaw('DATE(tallies.created_at) asc, tallies.nomor_polisi asc');
}
     public static function rekapPerGrade(string $nomorPolisi, string $tanggal): \Illuminate\Support\Collection
{
    $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];

    $rekap = Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
        ->selectRaw('
            LOWER(pallets.grade) as grade_key,
            pallets.grade,
            SUM(pallets.jumlah) as total_jumlah,
            SUM(pallets.volume) as total_volume
        ')
        ->where('tallies.nomor_polisi', $nomorPolisi)
        ->whereDate('tallies.created_at', $tanggal)
        ->groupBy('pallets.grade')
        ->get()
        ->keyBy('grade_key');

    return collect($grades)->map(function ($grade) use ($rekap) {
        $data = $rekap->get(strtolower($grade));
        return (object) [
            'grade' => strtoupper($grade),
            'total_jumlah' => $data?->total_jumlah ?? 0,
            'total_volume' => $data?->total_volume / 1000000 ?? 0,
        ];
    });
}
public static function hitungTotalTagihan(string $nomorPolisi, string $tanggal, \Illuminate\Support\Collection $costs): float
{
    return Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
        ->selectRaw('pallets.grade, SUM(pallets.volume) as total_volume')
        ->where('tallies.nomor_polisi', $nomorPolisi)
        ->whereDate('tallies.created_at', $tanggal)
        ->groupBy('pallets.grade')
        ->get()
        ->sum(function ($item) use ($costs) {
            $grade = strtoupper($item->grade);
            $harga = $costs[$grade]->harga ?? 0;
            return ($item->total_volume / 1000000) * $harga;
        });
}
public static function detailPallets(string $nomorPolisi, string $tanggal, int $perPage = 5)
{
    return Pallets::join('tallies', 'pallets.tally_id', '=', 'tallies.id')
        ->selectRaw('
            pallets.grade,
            pallets.tebal,
            pallets.lebar,
            pallets.panjang,
            SUM(pallets.jumlah) as total_jumlah,
            SUM(pallets.volume) / 1000000 as total_volume
        ')
        ->where('tallies.nomor_polisi', $nomorPolisi)
        ->whereDate('tallies.created_at', $tanggal)
        ->groupBy('pallets.grade', 'pallets.tebal', 'pallets.lebar', 'pallets.panjang')
        ->orderBy('pallets.grade', 'asc')
        ->orderBy('pallets.tebal', 'desc')
        ->orderBy('pallets.lebar', 'desc')
        ->paginate($perPage);
}

}
