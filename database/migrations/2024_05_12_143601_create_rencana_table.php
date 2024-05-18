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
        Schema::create('rencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->references('id')->on('unit')->onDelete('cascade');
            $table->date('tahun');
            $table->decimal('jumlah', 10,2)->nullable();
            $table->decimal('anggaran', 10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana');
    }
};
