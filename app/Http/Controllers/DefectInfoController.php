<?php

namespace App\Http\Controllers;

use App\Models\DataDefect;
use App\Models\DataProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefectInfoController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Query data defect dengan relasi ke produksi
        $query = DataDefect::with('produksi');

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('Tanggal_Produksi', $bulan);
        }

        // Filter berdasarkan tahun
        if ($tahun) {
            $query->whereYear('Tanggal_Produksi', $tahun);
        }

        $data = $query->orderBy('Tanggal_Produksi', 'desc')->get();

        // Get available years
        $availableYears = DataDefect::selectRaw('YEAR(Tanggal_Produksi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Summary Statistics
        $totalDefect = $data->sum('Jumlah_Cacat_perjenis');
        
        // Total produksi dari data yang ter-filter
        $totalProduksi = 0;
        if ($bulan || $tahun) {
            $produksiQuery = DataProduksi::query();
            if ($bulan) {
                $produksiQuery->whereMonth('Tanggal_Produksi', $bulan);
            }
            if ($tahun) {
                $produksiQuery->whereYear('Tanggal_Produksi', $tahun);
            }
            $totalProduksi = $produksiQuery->sum('Jumlah_Produksi');
        } else {
            $totalProduksi = DataProduksi::sum('Jumlah_Produksi');
        }

        $defectRate = $totalProduksi > 0 ? ($totalDefect / $totalProduksi) * 100 : 0;

        // PERBAIKAN: Format data untuk chart (key-value sederhana)
        // Group by Jenis Defect
        $jenisDefectGrouped = $data->groupBy('Jenis_Defect')
            ->map(function ($items) {
                return $items->sum('Jumlah_Cacat_perjenis');
            })
            ->sortByDesc(function ($value) {
                return $value;
            })
            ->toArray();

        // Group by Severity
        $severityGrouped = $data->groupBy('Severity')
            ->map(function ($items) {
                return $items->sum('Jumlah_Cacat_perjenis');
            })
            ->toArray();

        // PERBAIKAN UTAMA: Group by Product (Top 10)
        $productGrouped = $data->groupBy('Nama_Barang')
            ->map(function ($items) {
                return $items->sum('Jumlah_Cacat_perjenis');
            })
            ->sortByDesc(function ($value) {
                return $value;
            })
            ->take(10) // Ambil top 10 produk
            ->toArray();

        // Trend per tanggal
        $trendGrouped = $data->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->Tanggal_Produksi)->format('d/m/Y');
        })
            ->map(function ($items) {
                return $items->sum('Jumlah_Cacat_perjenis');
            })
            ->sortKeys()
            ->toArray();

        // Severity counts
        $criticalCount = $data->where('Severity', 'Critical')->sum('Jumlah_Cacat_perjenis');
        $majorCount = $data->where('Severity', 'Major')->sum('Jumlah_Cacat_perjenis');
        $minorCount = $data->where('Severity', 'Minor')->sum('Jumlah_Cacat_perjenis');

        return view('defect.index_spv', compact(
            'data',
            'bulan',
            'tahun',
            'availableYears',
            'totalDefect',
            'totalProduksi',
            'defectRate',
            'jenisDefectGrouped',
            'severityGrouped',
            'productGrouped',
            'trendGrouped',
            'criticalCount',
            'majorCount',
            'minorCount'
        ));
    }
}