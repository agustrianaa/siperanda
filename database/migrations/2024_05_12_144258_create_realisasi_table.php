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
        Schema::create('realisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_id')->references('id')->on('rencana')->onDelete('cascade')->nullable();
            $table->date('skedul')->nullable();
            $table->date('realisasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi');
    }
};
