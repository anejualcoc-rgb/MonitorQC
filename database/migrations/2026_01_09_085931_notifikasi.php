<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('notifikasi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penerima notif
        
        $table->string('judul');
        $table->text('pesan');
        $table->string('tipe')->default('info'); // approval, info, alert
        
        // --- INI PERUBAHAN PENTING ---
        // 1. Kolom reference_id menyimpan ID dari TempDataProduksi
        // 2. nullable() karena notifikasi tipe 'info' tidak butuh relasi ini
        $table->unsignedBigInteger('reference_id')->nullable();
        
        // 3. Foreign Key Optional (Jika ingin strict consistency)
        // Hati-hati: Jika TempDataProduksi dihapus, notifikasi approval ini juga hilang
        // $table->foreign('reference_id')->references('id')->on('temp_data_produksi')->onDelete('cascade');
        
        $table->json('data')->nullable(); // Sisa data lain (url, metadata)
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
