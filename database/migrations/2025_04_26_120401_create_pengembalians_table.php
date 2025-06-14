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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->integer('jumlah_brg_baik')->nullable();
            $table->integer('jumlah_brg_rusak')->nullable();
            $table->integer('jumlah_brg_hilang')->nullable();
            $table->date('tgl_pengembalian')->nullable();
            $table->uuid('peminjaman_id');
            $table->foreign('peminjaman_id')->references('id')->on('peminjamans')->restrictOnDelete()->restrictOnUpdate();
            $table->integer('keterangan')->nullable();
            $table->string('status')->default('Belum Di kembalikan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
