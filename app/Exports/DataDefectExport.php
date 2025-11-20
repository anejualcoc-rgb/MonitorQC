<?php

namespace App\Exports;

use App\Models\DataDefect;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataDefectExport implements FromCollection
{
    public function collection()
    {
        return DataDefect::all();
    }
}
