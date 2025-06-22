<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tallies extends Model
{
    protected $fillable =  [
        'no_register',
        'total_balken',
        'total_volume',
        'nomor_polisi',
        'tally_man',
        'status',
    ];

    public function pallet(){
        return $this->hasMany(Pallets::class,'tally_id');
    }
    public function kilndry(){
        return $this->belongsTo(KilnDry::class);
    }

}
