<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
    $table->id();    

    $table->date('periode_awal');
    $table->date('periode_akhir');

    $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
    $table->integer('jumlah_hari');

    $table->float('jumlah_lembur');
    $table->float('kekurangan_jam');

    $table->decimal('upah', 10, 2);
    $table->decimal('tunjangan', 10, 2);
    $table->decimal('premi_kehadiran', 10, 2);

    $table->decimal('total_upah', 12, 2);
    $table->decimal('total_lembur', 12, 2);
    $table->decimal('total_tunjangan', 12, 2);
    $table->decimal('total_premi_kehadiran', 12, 2);

    $table->decimal('potongan_bpjs', 12, 2);
    $table->decimal('potongan_kekurangan_jam', 12, 2);
    $table->decimal('penerimaan', 15, 2); // penerimaan akhir, bisa lebih besar

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
