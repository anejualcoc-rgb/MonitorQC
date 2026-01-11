<?php

namespace App\Exports;

use App\Models\DataProduksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DataProduksiExportManager implements FromQuery, WithHeadings, ShouldAutoSize
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
        $query = DataProduksi::query()
            ->select(
                'id',
                'Tanggal_Produksi',
                'User',               // Operator
                'Shift_Produksi',
                'Line_Produksi',
                'Jumlah_Produksi',
                'Target_Produksi',
                'Jumlah_Produksi_Cacat'
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
            'ID', 'Tanggal', 'Operator', 'Shift', 'Line', 'Aktual', 'Target', 'Total Cacat'
        ];
    }
}