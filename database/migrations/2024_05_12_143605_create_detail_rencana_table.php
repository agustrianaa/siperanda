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
        Schema::create('detail_rencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_id')->references('id')->on('rencana')->onDelete('cascade');
            $table->unsignedBigInteger('noparent_id')->nullable();
            $table->foreign('noparent_id')->references('id')->on('detail_rencana')->onDelete('cascade');
            $table->foreignId('kode_komponen_id')->references('id')->on('kode_komponen')->onDelete('cascade');
            $table->foreignId('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
            $table->string('volume');
            $table->decimal('harga');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_rencana');
    }
};
