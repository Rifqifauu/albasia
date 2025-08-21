<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TallyBalken extends Model
{
    protected $fillable =  [
        'no_register',
        'total_balken',
        'total_volume',
        'nomor_polisi',
        'tally_man',
        'status',
        'kiln_dries_id',
    ];

    protected $table = 'tally_balken';
    public function pallet(){
        return $this->hasMany(PalletBalken::class,'tally_id');
    }
    public function kilndry(){
        return $this->belongsTo(KilnDry::class, 'kiln_dries_id');
    }

}
