<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('data_defect', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_produksi_id');
            $table->date('Tanggal_Produksi')->nullable(); 
            $table->string('Nama_Barang');
            $table->string('Jenis_Defect');
            $table->integer('Jumlah_Cacat_perjenis');
            $table->string('Severity');
            $table->timestamps();

            $table->foreign('data_produksi_id')
                ->references('id')
                ->on('data_produksi')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
