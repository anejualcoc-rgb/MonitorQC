<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullDataExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data Produksi' => new DataProduksiExport(),
            'Data Defect' => new DataDefectExport(),
        ];
    }
}
