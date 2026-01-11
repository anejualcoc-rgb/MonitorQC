<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataExportManager implements WithMultipleSheets
{
    protected $type;
    protected $filterData;

    public function __construct($type = null, $filterData = [])
    {
        $this->type = $type;
        $this->filterData = $filterData;
    }

    /**
     * Disini kita atur agar file Excel punya 2 Sheet (Tab).
     */
    public function sheets(): array
    {
        return [
            // Sheet 1: Data Produksi
            'Laporan Produksi' => new DataProduksiExportManager($this->type, $this->filterData),
            
            // Sheet 2: Data Defect
            'Laporan Defect'   => new DataDefectExportManager($this->type, $this->filterData),
        ];
    }
}