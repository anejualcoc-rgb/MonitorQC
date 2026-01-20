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
        $table->string('tipe')->default('info'); 
        $table->unsignedBigInteger('reference_id')->nullable();
        $table->json('data')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
