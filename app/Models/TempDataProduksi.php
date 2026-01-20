<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempDataProduksi extends Model
{
    use HasFactory;

    protected $table = 'temp_data_produksi';

    protected $fillable = [
        'User', 
        'Tanggal_Produksi',
        'Shift_Produksi',
        'Line_Produksi',
        'Jumlah_Produksi',
        'Target_Produksi',
        'Jumlah_Produksi_Cacat',
        'input_by_user_id',
        'status_approval',
        'catatan_revisi'
    ];

    public function inputter()
    {
        return $this->belongsTo(User::class, 'input_by_user_id');
    }
}