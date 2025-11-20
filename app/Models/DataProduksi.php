<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProduksi extends Model
{
    protected $table = 'data_produksi';
    protected $fillable = [
        'User', 'Tanggal_Produksi', 'Shift_Produksi',
        'Line_Produksi', 'Jumlah_Produksi', 'Target_Produksi',
        'Jumlah_Produksi_Cacat'
    ];

    public function defect()
    {
        return $this->hasMany(DataDefect::class, 'data_produksi_id');
    }
}
