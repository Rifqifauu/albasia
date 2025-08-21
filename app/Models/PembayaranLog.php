<?php

namespace App\Models;
use App\Models\KubikasiLog;
use Illuminate\Database\Eloquent\Model;


class PembayaranLog extends KubikasiLog
{
protected $table = 'pembayaran_log';
  protected $fillable = [
        'no_polisi',
        'quantity',
        'total_tagihan',
        'status',
        'tanggal_tally'
    ];
   public static function generateFromKubikasi(\Illuminate\Support\Collection $costs)
{
    $dataKubikasi = \App\Models\KubikasiLog::queryWithTotalTagihanDanJumlah()->get();

    foreach ($dataKubikasi as $item) {
        // UBAH: dari static:: jadi PembayaranLog::
        PembayaranLog::updateOrCreate(
            [
                'no_polisi' => $item->nomor_polisi,
                'tanggal_tally' => $item->created_at,
            ],
            [
                'quantity' => $item->total_jumlah,
                'total_tagihan' => $item->total_tagihan,
                'status' => 'pending', // jangan lupa set status default
            ]
        );
    }
}


}
