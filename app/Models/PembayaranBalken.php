<?php

namespace App\Models;
use App\Models\KubikasiBalken;
use Illuminate\Database\Eloquent\Model;


class PembayaranBalken extends KubikasiBalken
{
protected $table = 'pembayaran_balken';
  protected $fillable = [
        'no_polisi',
        'quantity',
        'total_tagihan',
        'status',
        'tanggal_tally'
    ];
   public static function generateFromKubikasi(\Illuminate\Support\Collection $costs)
{
$dataKubikasi = \App\Models\KubikasiBalken::queryWithTotalTagihanDanJumlah()->get();

    foreach ($dataKubikasi as $item) {


        PembayaranBalken::updateOrCreate(
            [
                'no_polisi' => $item->nomor_polisi,
                'tanggal_tally' => $item->created_at,
            ],
            [
                'quantity' => $item->total_jumlah,
                'total_tagihan' => $item->total_tagihan,
            ]
        );
    }
}


}
