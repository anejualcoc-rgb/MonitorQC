<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifikasi'; // Sesuai nama tabel di migrasi

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'data',
        'is_read',
        'read_at',
    ];

    // Mengubah kolom JSON 'data' menjadi array otomatis
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}