<?php

namespace App\Exports;

use App\Models\DataDefect;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DataDefectExportManager implements FromQuery, WithHeadings, ShouldAutoSize
{
    protected $type;
    protected $filterData;

    public function __construct($type, $filterData)
    {
        $this->type = $type;
        $this->filterData = $filterData;
    }

    public function query()
    {
        $query = DataDefect::query()
            ->select(
                'id', 
                'Tanggal_Produksi', 
                'Nama_Barang',
                'Jenis_Defect', 
                'Jumlah_Cacat_perjenis', 
                'Severity'
            );

        // --- Logika Filter ---
        if ($this->type == 'daily' && !empty($this->filterData['date'])) {
            $query->whereDate('Tanggal_Produksi', $this->filterData['date']);
        } 
        elseif ($this->type == 'monthly' && !empty($this->filterData['month'])) {
            $date = Carbon::parse($this->filterData['month']);
            $query->whereYear('Tanggal_Produksi', $date->year)
                  ->whereMonth('Tanggal_Produksi', $date->month);
        } 
        elseif ($this->type == 'yearly' && !empty($this->filterData['year'])) {
            $query->whereYear('Tanggal_Produksi', $this->filterData['year']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID', 'Tanggal', 'Nama Produk', 'Jenis Defect', 'Jumlah', 'Severity'
        ];
    }
}