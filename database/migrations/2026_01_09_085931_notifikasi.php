<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            // Relasi ke user penerima
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Judul notifikasi
            $table->string('judul');

            // Isi pesan notifikasi
            $table->text('pesan');

            // Tipe notifikasi (qc, defect, sistem, reminder, dll)
            $table->string('tipe')->nullable();

            // Data tambahan (misal: id defect, workstation, dll)
            $table->json('data')->nullable();

            // Status dibaca
            $table->boolean('is_read')->default(false);

            // Waktu dibaca
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
