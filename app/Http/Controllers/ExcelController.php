<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduksi;
use App\Models\DataDefect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public function index()
        {
            $data = DataProduksi::orderBy('Tanggal_Produksi', 'desc')
                ->orderBy('Shift_Produksi')
                ->get();

            $data_defect = DataDefect::orderBy('Tanggal_Produksi', 'desc')
                ->orderBy('Severity', 'desc')
                ->get();

            $stats = [
                'total_produksi' => $data->sum('Jumlah_Produksi'),
                'total_target' => $data->sum('Target_Produksi'),
                'total_cacat' => $data->sum('Jumlah_Produksi_Cacat'),
                'persentase_cacat' => $data->sum('Jumlah_Produksi') > 0 
                    ? ($data->sum('Jumlah_Produksi_Cacat') / $data->sum('Jumlah_Produksi')) * 100 
                    : 0,
                'achievement' => $data->sum('Target_Produksi') > 0 
                    ? ($data->sum('Jumlah_Produksi') / $data->sum('Target_Produksi')) * 100 
                    : 0,
            ];

            $produksi_per_tanggal = $data->groupBy('Tanggal_Produksi')->map(function ($items) {
                return [
                    'tanggal' => $items->first()->Tanggal_Produksi,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'target' => $items->sum('Target_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            $defect_by_severity = $data_defect->groupBy('Severity')->map(function ($items, $severity) {
                return [
                    'severity' => $severity,
                    'jumlah' => $items->sum('Jumlah_Cacat_perjenis'),
                ];
            })->values();

            $defect_by_type = $data_defect->groupBy('Jenis_Defect')->map(function ($items, $jenis) {
                return [
                    'jenis' => $jenis,
                    'jumlah' => $items->sum('Jumlah_Cacat_perjenis'),
                ];
            })->sortByDesc('jumlah')->take(10)->values();

            $produksi_per_line = $data->groupBy('Line_Produksi')->map(function ($items, $line) {
                return [
                    'line' => $line,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            $produksi_per_shift = $data->groupBy('Shift_Produksi')->map(function ($items, $shift) {
                return [
                    'shift' => $shift,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            return view('dashboard_staff', compact(
                'data', 
                'data_defect',
                'stats',
                'produksi_per_tanggal',
                'defect_by_severity',
                'defect_by_type',
                'produksi_per_line',
                'produksi_per_shift'
            ));
        }

    public function index_spv()
        {
            $data = DataProduksi::orderBy('Tanggal_Produksi', 'desc')
                ->orderBy('Shift_Produksi')
                ->get();
   
            $data_defect = DataDefect::orderBy('Tanggal_Produksi', 'desc')
                ->orderBy('Severity', 'desc')
                ->get();

            $stats = [
                'total_produksi' => $data->sum('Jumlah_Produksi'),
                'total_target' => $data->sum('Target_Produksi'),
                'total_cacat' => $data->sum('Jumlah_Produksi_Cacat'),
                'persentase_cacat' => $data->sum('Jumlah_Produksi') > 0 
                    ? ($data->sum('Jumlah_Produksi_Cacat') / $data->sum('Jumlah_Produksi')) * 100 
                    : 0,
                'achievement' => $data->sum('Target_Produksi') > 0 
                    ? ($data->sum('Jumlah_Produksi') / $data->sum('Target_Produksi')) * 100 
                    : 0,
            ];

            $produksi_per_tanggal = $data->groupBy('Tanggal_Produksi')->map(function ($items) {
                return [
                    'tanggal' => $items->first()->Tanggal_Produksi,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'target' => $items->sum('Target_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            $defect_by_severity = $data_defect->groupBy('Severity')->map(function ($items, $severity) {
                return [
                    'severity' => $severity,
                    'jumlah' => $items->sum('Jumlah_Cacat_perjenis'),
                ];
            })->values();

            $defect_by_type = $data_defect->groupBy('Jenis_Defect')->map(function ($items, $jenis) {
                return [
                    'jenis' => $jenis,
                    'jumlah' => $items->sum('Jumlah_Cacat_perjenis'),
                ];
            })->sortByDesc('jumlah')->take(10)->values();

            $produksi_per_line = $data->groupBy('Line_Produksi')->map(function ($items, $line) {
                return [
                    'line' => $line,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            $produksi_per_shift = $data->groupBy('Shift_Produksi')->map(function ($items, $shift) {
                return [
                    'shift' => $shift,
                    'produksi' => $items->sum('Jumlah_Produksi'),
                    'cacat' => $items->sum('Jumlah_Produksi_Cacat'),
                ];
            })->values();

            return view('dashboard_spv', compact(
                'data', 
                'data_defect',
                'stats',
                'produksi_per_tanggal',
                'defect_by_severity',
                'defect_by_type',
                'produksi_per_line',
                'produksi_per_shift'
            ));
        }

    public function uploadPage()
    {
        return view('upload'); 
    }

}
