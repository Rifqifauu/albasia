<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payrolls extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'periode_awal',
        'periode_akhir',
        'jumlah_hari',
        'jumlah_lembur',
        'kekurangan_jam',
        'rate_potongan_jam',
        'upah',
        'tunjangan',
        'premi_kehadiran',
        'total_upah',
        'total_lembur',
        'total_tunjangan',
        'total_premi_kehadiran',
        'potongan_bpjs',
        'potongan_kekurangan_jam',
        'penerimaan',
    ];

    // Relasi ke karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

  public function calculateFromAttendances(): void
{
    $attendances = $this->employee
        ->attendances()
        ->whereBetween('tanggal', [$this->periode_awal, $this->periode_akhir])
        ->get();

    $this->jumlah_hari = $attendances->count();
    $this->jumlah_lembur = $attendances->sum('lembur');
    $this->kekurangan_jam = $attendances->sum('kekurangan_jam');

    $this->total_upah = $this->upah * $this->jumlah_hari;
    $this->total_lembur = $this->jumlah_lembur * ($this->upah / 8);
    $this->total_tunjangan = $this->tunjangan * $this->jumlah_hari;
    $this->total_premi_kehadiran = $this->premi_kehadiran * $this->jumlah_hari;

    $this->potongan_kekurangan_jam = $this->rate_potongan_jam * $this->kekurangan_jam;

    $this->penerimaan = (
        $this->total_upah +
        $this->total_lembur +
        $this->total_tunjangan +
        $this->total_premi_kehadiran
    ) - (
        $this->potongan_bpjs + $this->potongan_kekurangan_jam
    );
}


protected static function booted()
{
    static::creating(function ($payroll) {
        $payroll->calculateFromAttendances();
    });

    static::updating(function ($payroll) {
        $payroll->calculateFromAttendances();
    });
}

}
