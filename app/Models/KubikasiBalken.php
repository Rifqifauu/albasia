<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class KubikasiBalken extends TallyBalken
{
     protected $table = 'tally_balken';

     public static function queryWithTotalTagihan(): Builder
{
    return static::query()
        ->leftJoin('pallet_balken', 'tally_balken.id', '=', 'pallet_balken.tally_id')
        ->leftJoinSub(
            \DB::table('costs')
                ->selectRaw('grade, harga')
                ->where('tipe', 'balken'),
            'filtered_costs',
            fn($join) => $join->on(\DB::raw('UPPER(pallet_balken.grade)'), '=', 'filtered_costs.grade')
        )
        ->selectRaw('
            MIN(tally_balken.id) as id,
            tally_balken.nomor_polisi,
            DATE(tally_balken.created_at) as created_at,
            COALESCE(SUM(pallet_balken.volume /1000000 * filtered_costs.harga), 0) as total_tagihan
        ')
        ->groupByRaw('tally_balken.nomor_polisi, DATE(tally_balken.created_at)')
        ->orderByRaw('DATE(tally_balken.created_at) asc, tally_balken.nomor_polisi asc');
}
     public static function rekapPerGrade(string $nomorPolisi, string $tanggal): \Illuminate\Support\Collection
{
    $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];
    $rekap = PalletBalken::join('tally_balken', 'pallet_balken.tally_id', '=', 'tally_balken.id')
        ->selectRaw('
            LOWER(pallet_balken.grade) as grade_key,
            pallet_balken.grade,
            SUM(pallet_balken.jumlah) as total_jumlah,
            SUM(pallet_balken.volume) as total_volume
        ')
        ->where('tally_balken.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_balken.created_at', $tanggal)
        ->groupBy('pallet_balken.grade')
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

public static function rekapPerGradeInKilnDry(string $kilndries_id){
    $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];
    $rekap = PalletBalken::join('tally_balken', 'pallet_balken.tally_id', '=', 'tally_balken.id')
        ->selectRaw('
            LOWER(pallet_balken.grade) as grade_key,
            pallet_balken.grade,
            SUM(pallet_balken.jumlah) as total_jumlah,
            SUM(pallet_balken.volume) as total_volume
        ')
        ->where('tally_balken.kiln_dries_id', $kilndries_id)
        ->groupBy('pallet_balken.grade')
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
    return PalletBalken::join('tally_balken', 'pallet_balken.tally_id', '=', 'tally_balken.id')
        ->selectRaw('pallet_balken.grade, SUM(pallet_balken.volume) as total_volume')
        ->where('tally_balken.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_balken.created_at', $tanggal)
        ->groupBy('pallet_balken.grade')
        ->get()
        ->sum(function ($item) use ($costs) {
            $grade = strtoupper($item->grade);
            $harga = $costs[$grade]->harga ?? 0;
            return ($item->total_volume / 1000000) * $harga;
        });
}
public static function detailpallet_balken(string $nomorPolisi, string $tanggal, int $perPage = 5)
{
    return PalletBalken::join('tally_balken', 'pallet_balken.tally_id', '=', 'tally_balken.id')
        ->selectRaw('
            pallet_balken.grade,
            pallet_balken.tebal,
            pallet_balken.lebar,
            pallet_balken.panjang,
            SUM(pallet_balken.jumlah) as total_jumlah,
            SUM(pallet_balken.volume) / 1000000 as total_volume
        ')
        ->where('tally_balken.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_balken.created_at', $tanggal)
        ->groupBy('pallet_balken.grade', 'pallet_balken.tebal', 'pallet_balken.lebar', 'pallet_balken.panjang')
        ->orderBy('pallet_balken.grade', 'asc')
        ->orderBy('pallet_balken.tebal', 'desc')
        ->orderBy('pallet_balken.lebar', 'desc')
        ->paginate($perPage);
}

}
