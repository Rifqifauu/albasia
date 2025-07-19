<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class KubikasiLog extends TallyLog
{
     protected $table = 'tally_log';

     public static function queryWithTotalTagihan(): Builder
{
    return static::query()
        ->leftJoin('pallet_log', 'tally_log.id', '=', 'pallet_log.tally_id')
        ->leftJoinSub(
            \DB::table('costs')
                ->selectRaw('grade, harga')
                ->where('tipe', 'log'),
            'filtered_costs',
            fn($join) => $join->on(\DB::raw('UPPER(pallet_log.grade)'), '=', 'filtered_costs.grade')
        )
        ->selectRaw('
            MIN(tally_log.id) as id,
            tally_log.nomor_polisi,
            DATE(tally_log.created_at) as created_at,
            COALESCE(SUM(pallet_log.volume /1000000 * filtered_costs.harga), 0) as total_tagihan
        ')
        ->groupByRaw('tally_log.nomor_polisi, DATE(tally_log.created_at)')
        ->orderByRaw('DATE(tally_log.created_at) asc, tally_log.nomor_polisi asc');
}
     public static function rekapPerGrade(string $nomorPolisi, string $tanggal): \Illuminate\Support\Collection
{
    $grades = ['kotak', 'ongrade', 'allgrade', 'ds4', 'afkir'];
    $rekap = Palletlog::join('tally_log', 'pallet_log.tally_id', '=', 'tally_log.id')
        ->selectRaw('
            LOWER(pallet_log.grade) as grade_key,
            pallet_log.grade,
            SUM(pallet_log.jumlah) as total_jumlah,
            SUM(pallet_log.volume) as total_volume
        ')
        ->where('tally_log.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_log.created_at', $tanggal)
        ->groupBy('pallet_log.grade')
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
    $rekap = Palletlog::join('tally_log', 'pallet_log.tally_id', '=', 'tally_log.id')
        ->selectRaw('
            LOWER(pallet_log.grade) as grade_key,
            pallet_log.grade,
            SUM(pallet_log.jumlah) as total_jumlah,
            SUM(pallet_log.volume) as total_volume
        ')
        ->where('tally_log.kiln_dries_id', $kilndries_id)
        ->groupBy('pallet_log.grade')
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
    return Palletlog::join('tally_log', 'pallet_log.tally_id', '=', 'tally_log.id')
        ->selectRaw('pallet_log.grade, SUM(pallet_log.volume) as total_volume')
        ->where('tally_log.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_log.created_at', $tanggal)
        ->groupBy('pallet_log.grade')
        ->get()
        ->sum(function ($item) use ($costs) {
            $grade = strtoupper($item->grade);
            $harga = $costs[$grade]->harga ?? 0;
            return ($item->total_volume / 1000000) * $harga;
        });
}
public static function detailpallet_log(string $nomorPolisi, string $tanggal, int $perPage = 5)
{
    return Palletlog::join('tally_log', 'pallet_log.tally_id', '=', 'tally_log.id')
        ->selectRaw('
            pallet_log.grade,
            pallet_log.tebal,
            pallet_log.lebar,
            pallet_log.panjang,
            SUM(pallet_log.jumlah) as total_jumlah,
            SUM(pallet_log.volume) / 1000000 as total_volume
        ')
        ->where('tally_log.nomor_polisi', $nomorPolisi)
        ->whereDate('tally_log.created_at', $tanggal)
        ->groupBy('pallet_log.grade', 'pallet_log.tebal', 'pallet_log.lebar', 'pallet_log.panjang')
        ->orderBy('pallet_log.grade', 'asc')
        ->orderBy('pallet_log.tebal', 'desc')
        ->orderBy('pallet_log.lebar', 'desc')
        ->paginate($perPage);
}

}
