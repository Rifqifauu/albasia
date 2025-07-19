<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalletLog extends Model
{
protected $fillable = [
    'tebal',
    'lebar',
    'panjang',
    'volume',
    'jumlah',
    'grade',

    ];
    protected $table = 'pallet_log';
    //
    public function tally(){
        return $this->belongsTo(TallyLog::class, 'tally_id');
    }}
