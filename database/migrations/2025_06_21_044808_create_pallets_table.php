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
        Schema::create('pallets', function (Blueprint $table) {
            $table->id(); // Gunakan ID biasa untuk primary key
            $table->foreignId('tally_id')->constrained('tallies')->onDelete('cascade'); 
            $table->string('nomor_pallet')->unique(); 
            $table->string('tebal');
            $table->string('lebar');
            $table->string('panjang');
            $table->string('volume');
            $table->string('jumlah');
            $table->string('grade');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
