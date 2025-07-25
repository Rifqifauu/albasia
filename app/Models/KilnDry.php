<?php

namespace App\Models;

use Filament\Tables\Filters\Concerns\HasRelationship;
use Illuminate\Database\Eloquent\Model;

class KilnDry extends Model
{
    protected $table = 'kiln_dries';
    protected $fillable = [
        'periode_kd',
        'kode_kd',
        'tanggal_bakar',
        'jam_bakar',
        'perkiraan_bongkar',
        'tanggal_bongkar',
        'keterangan',
    ];
   public function balken()

{
    return $this->hasMany(TallyBalken::class, 'kiln_dries_id');
}
   public function log()

{
    return $this->hasMany(TallyLog::class, 'kiln_dries_id');
}

}
