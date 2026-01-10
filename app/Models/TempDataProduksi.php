<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempDataProduksi extends Model
{
    protected $table = 'temp_data_produksi';
    protected $guarded = [];

    public function inputer()
    {
        return $this->belongsTo(User::class, 'input_by_user_id');
    }
}