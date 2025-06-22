<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pallets extends Model
{
    protected $fillable = [
        'tebal',
    'lebar',
    'panjang',
    'volume',
    'jumlah',
    'grade',
    
    ];
    //
    public function tally(){
        return $this->belongsTo(Tallies::class, 'tally_id');
    }
}
