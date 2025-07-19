<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallyLog extends Model
{
    protected $fillable =  [
        'no_register',
        'total_balken',
        'total_volume',
        'nomor_polisi',
        'tally_man',
        'status',
    ];

    protected $table = 'tally_log';
    public function pallet(){
        return $this->hasMany(PalletLog::class,'tally_id');
    }
    public function kilndry(){
        return $this->belongsTo(KilnDry::class);
    }
}
