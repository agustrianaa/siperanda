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
            $table->foreignId('kode_komponen_id')->references('id')->on('kode_komponen')->onDelete('cascade')->nullable();
            $table->foreignId('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
            $table->string('volume');
            $table->decimal('harga', 15,2)->nullable();
            $table->decimal('total', 15,2)->nullable();;
            $table->text('uraian')->nullable();
            $table->boolean('is_revised')->nullable();
            $table->boolean('is_revised2')->nullable();
            $table->boolean('is_revised3')->nullable();
            $table->string('revisi_keterangan')->nullable();
            $table->string('created_by')->nullable();
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
