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
        Schema::create('pemusnaans', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->integer('jumlah_pemusnaan')->nullable();
            $table->date('tanggal_pemusnaan')->nullable(); 
            $table->string('gambar_pemusnaan')->nullable()->default('-');
            $table->text('keterangan')->nullable()->default('-');
            $table->uuid('rusak_id');
            $table->foreign('rusak_id')->references('id')->on('rusaks')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemusnaans');
    }
};
