<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'masuk',
        'keluar',
        'durasi_kerja',
        'kekurangan_jam',
        'lembur',
        'tanggal',
    ];

    protected $casts = [
        'masuk' => 'datetime:H:i',
        'keluar' => 'datetime:H:i',
        'tanggal' => 'date',
    ];
protected static function booted()
{
    static::saving(function ($attendance) {
        $masuk = \Carbon\Carbon::parse($attendance->masuk);
        $keluar = \Carbon\Carbon::parse($attendance->keluar);

        $durasiJam = $masuk->diffInMinutes($keluar) / 60; // hasil float

        $attendance->durasi_kerja = $durasiJam;

        $targetJam = 8;
        $selisih = $durasiJam - $targetJam;

        if ($selisih >= 0) {
            $attendance->lembur = $selisih;
            $attendance->kekurangan_jam = 0;
        } else {
            $attendance->lembur = 0;
            $attendance->kekurangan_jam = abs($selisih);
        }
    });
}

    // Relasi ke karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
