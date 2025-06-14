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
        Schema::create('rusaks', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->enum('jenis_brg_rusak', ['elektronik', 'mobiler', 'lainnya']);
            $table->integer('jumlah_brg_rusak');
            $table->string('gambar_brg_rusak')->nullable()->default('-'); 
            $table->date('tgl_rusak');
            $table->text('keterangan')->nullable()->default('-');
            $table->string('status')->default('Rusak');

            $table->uuid('elektronik_id')->nullable();
            $table->uuid('lainnya_id')->nullable();
            $table->uuid('mobiler_id')->nullable();

        
            $table->foreign('elektronik_id')->references('id')->on('elektroniks')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('lainnya_id')->references('id')->on('lainnyas')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('mobiler_id')->references('id')->on('mobilers')->restrictOnDelete()->restrictOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rusaks');
    }
};
