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
            // ... Copy semua kolom dari data_produksi di sini ...
            $table->date('Tanggal_Produksi');
            $table->string('Line_Produksi');
            $table->string('Shift_Produksi');
            $table->integer('Jumlah_Produksi');
            $table->integer('Target_Produksi');
            $table->integer('Jumlah_Produksi_Cacat');
            
            // Kolom Tambahan untuk Approval
            $table->foreignId('input_by_user_id')->constrained('users'); // Siapa yang input
            $table->string('status_approval')->default('pending'); // pending, approved, rejected
            $table->text('catatan_revisi')->nullable(); // Jika direject manager
            $table->timestamps();
        });
    }
};
