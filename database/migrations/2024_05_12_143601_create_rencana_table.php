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
            $table->foreignId('unit_id')->references('id')->on('unit')->onDelete('cascade')->nullable();
            $table->date('tahun');
            $table->decimal('jumlah', 15,2)->nullable(); //untuk jumlah seluruh RKA per Unit
            $table->decimal('anggaran', 15,2)->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'revisi']);
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
