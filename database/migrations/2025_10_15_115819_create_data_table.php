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
        Schema::create('data_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('User');
            $table->date('Tanggal_Produksi');
            $table->string('Shift_Produksi');
            $table->string('Line_Produksi');
            $table->integer('Jumlah_Produksi');
            $table->integer('Target_Produksi');
            $table->integer('Jumlah_Produksi_Cacat')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
