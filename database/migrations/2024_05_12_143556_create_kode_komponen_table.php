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
        Schema::create('kode_komponen', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('kode_parent');
            $table->foreignId('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            $table->string('uraian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_komponen');
    }
};
