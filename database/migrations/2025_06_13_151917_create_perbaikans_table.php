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
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->integer('jumlah_perbaikan')->nullable();
            $table->date('tanggal_perbaikan')->nullable(); 
            $table->integer('biaya_perbaikan')->nullable();
            $table->text('keterangan')->nullable()->default('-');
            $table->string('status')->nullable()->default('Dalam Perbaikan');
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
        Schema::dropIfExists('perbaikans');
    }
};
