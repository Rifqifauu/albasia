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
        Schema::create('tallies', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('kiln_dries_id')->nullable()->constrained('kiln_dries')->onDelete('set null');
            $table->string('kode_tally')->unique();; 
            $table->string('nomor_polisi'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tallies');
    }
};
