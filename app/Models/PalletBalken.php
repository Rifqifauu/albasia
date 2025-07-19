<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalletBalken extends Model
{
    protected $fillable = [
    'tebal',
    'lebar',
    'panjang',
    'volume',
    'jumlah',
    'grade',

    ];
    protected $table = 'pallet_balken';
    //
    public function tally(){
        return $this->belongsTo(TallyBalken::class, 'tally_id');
    }
}
