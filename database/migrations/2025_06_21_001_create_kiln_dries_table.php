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
        Schema::create('kiln_dries', function (Blueprint $table) {
            $table->id();
            $table->string('periode_kd');
            $table->string('kode_kd')->unique();;
            $table->date('perkiraan_bongkar');
            $table->date('tanggal_bongkar');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kiln_dries');
    }
};
