<?php

namespace App\Exports;

use App\Models\DataProduksi;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataProduksiExport implements FromCollection
{
    public function collection()
    {
        return DataProduksi::all();
    }
}
