<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDefect extends Model
{
    use HasFactory;

    protected $table = 'data_defect';

    protected $fillable = [
        'data_produksi_id',
        'Tanggal_Produksi',
        'Nama_Barang',
        'Jenis_Defect',
        'Jumlah_Cacat_perjenis',
        'Severity'
    ];

    public function produksi()
    {
        return $this->belongsTo(DataProduksi::class, 'data_produksi_id');
    }
}
