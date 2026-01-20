<?php

namespace App\Http\Controllers;

use App\Models\DataProduksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = DataProduksi::query();
        
        // Default: tampilkan semua data
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        
        // Filter jika ada
        if ($bulan && $tahun) {
            $query->whereYear('Tanggal_Produksi', $tahun)
                  ->whereMonth('Tanggal_Produksi', $bulan);
        }
        
        $data = $query->orderBy('Tanggal_Produksi', 'desc')->get();
        
        // Hitung summary
        $totalProduksi = $data->sum('Jumlah_Produksi');
        $totalTarget = $data->sum('Target_Produksi');
        $totalCacat = $data->sum('Jumlah_Produksi_Cacat');
        $persentaseCacat = $totalProduksi > 0 ? ($totalCacat / $totalProduksi) * 100 : 0;
        $achievement = $totalTarget > 0 ? ($totalProduksi / $totalTarget) * 100 : 0;
        
        // Trend data (group by tanggal)
        $trendGrouped = $data->groupBy(function($item) {
            return Carbon::parse($item->Tanggal_Produksi)->format('Y-m-d');
        })->map(function($group) {
            return [
                'produksi' => $group->sum('Jumlah_Produksi'),
                'target' => $group->sum('Target_Produksi'),
                'cacat' => $group->sum('Jumlah_Produksi_Cacat')
            ];
        });
        
        // Distribusi by line
        $lineGrouped = $data->groupBy('Line_Produksi')->map(function($g) {
            return [
                'produksi' => $g->sum('Jumlah_Produksi'),
                'target' => $g->sum('Target_Produksi'),
                'cacat' => $g->sum('Jumlah_Produksi_Cacat')
            ];
        });
        
        // Shift summary
        $shiftGrouped = $data->groupBy('Shift_Produksi')->map(function($g) {
            return [
                'produksi' => $g->sum('Jumlah_Produksi'),
                'target' => $g->sum('Target_Produksi')
            ];
        });
        
        // Data untuk filter dropdown
        $availableYears = DataProduksi::selectRaw('YEAR(Tanggal_Produksi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('produksi.index_spv', compact(
            'data',
            'totalProduksi',
            'totalTarget',
            'totalCacat',
            'persentaseCacat',
            'achievement',
            'trendGrouped',
            'lineGrouped',
            'shiftGrouped',
            'availableYears',
            'bulan',
            'tahun'
        ));
    }

    public function show($id)
    {
        $data = DataProduksi::findOrFail($id);

        $achievement = $data->Target_Produksi > 0 
            ? ($data->Jumlah_Produksi / $data->Target_Produksi) * 100 
            : 0;
            
        $defectRate = $data->Jumlah_Produksi > 0 
            ? ($data->Jumlah_Produksi_Cacat / $data->Jumlah_Produksi) * 100 
            : 0;

        return view('showdetail', compact('data', 'achievement', 'defectRate'));
    }
}