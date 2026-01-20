<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_data_produksi', function (Blueprint $table) {
            $table->id();
            
            $table->string('User'); 
            $table->date('Tanggal_Produksi');
            $table->string('Line_Produksi');
            $table->string('Shift_Produksi');
            $table->integer('Jumlah_Produksi');
            $table->integer('Target_Produksi');
            $table->integer('Jumlah_Produksi_Cacat');
            
            $table->foreignId('input_by_user_id')->constrained('users'); 
            $table->string('status_approval')->default('pending'); 
            $table->text('catatan_revisi')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_data_produksi');
    }
};