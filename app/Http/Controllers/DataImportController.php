<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DataProduksi;
use App\Models\DataDefect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataImportController extends Controller
{
    protected function parseDate($value)
    {
        if (!$value) return null;

        try {
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', trim($value))) {
                return Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');

        try {
            DB::beginTransaction();
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $index => $sheetName) {
                if (strtolower(trim($sheetName)) === 'produksi') {
                    $sheet = $spreadsheet->getSheet($index);
                    $rows = $sheet->toArray(null, true, true, false);

                    for ($r = 1; $r < count($rows); $r++) {
                        $row = $rows[$r];

    
                        if (empty($row[1]) && empty($row[2]) && empty($row[3])) {
                            continue;
                        }

                        $tanggal = $this->parseDate($row[1] ?? null);

                        DataProduksi::create([
                            'User' => $row[0] ?? null,
                            'Tanggal_Produksi' => $tanggal,
                            'Shift_Produksi' => $row[2] ?? null,
                            'Line_Produksi' => $row[3] ?? null,
                            'Jumlah_Produksi' => intval($row[4] ?? 0),
                            'Target_Produksi' => intval($row[5] ?? 0),
                            'Jumlah_Produksi_Cacat' => 0, 
                        ]);
                    }
                }
            }

            
            foreach ($sheetNames as $index => $sheetName) {
                if (stripos($sheetName, 'defect') !== false) {
                    $sheet = $spreadsheet->getSheet($index);
                    $rows = $sheet->toArray(null, true, true, false);

                    for ($r = 1; $r < count($rows); $r++) {
                        $row = $rows[$r];

                        // Skip empty rows
                        if (empty($row[0]) && empty($row[2])) {
                            continue;
                        }

                        $tanggal = $this->parseDate($row[0] ?? null);
                        $namaBarang = $row[1] ?? null;
                        $jenis = $row[2] ?? null;
                        $jumlah = intval($row[3] ?? 0);
                        $severity = $row[4] ?? null;
                        $line = $row[5] ?? null;
                        $shift = $row[6] ?? null;

                        $produksi = null;
                        if ($tanggal) {
                            $query = DataProduksi::whereDate('Tanggal_Produksi', $tanggal);
                            
                            
                            if ($line) {
                                $query->where('Line_Produksi', $line);
                            }
                            
                            
                            if ($shift) {
                                $query->where('Shift_Produksi', $shift);
                            }
                            
                            $produksi = $query->first();
                            if (!$produksi) {
                                $produksi = DataProduksi::whereDate('Tanggal_Produksi', $tanggal)->first();
                            }
                        }

                        DataDefect::create([
                            'data_produksi_id' => $produksi ? $produksi->id : null,
                            'Tanggal_Produksi' => $tanggal,
                            'Nama_Barang' => $namaBarang,
                            'Jenis_Defect' => $jenis,
                            'Jumlah_Cacat_perjenis' => $jumlah,
                            'Severity' => $severity,
                        ]);
                    }
                }
            }

            $defectSums = DataDefect::select('data_produksi_id', DB::raw('SUM(Jumlah_Cacat_perjenis) as total'))
                ->whereNotNull('data_produksi_id')
                ->groupBy('data_produksi_id')
                ->get();

            foreach ($defectSums as $sum) {
                DataProduksi::where('id', $sum->data_produksi_id)
                    ->update([
                        'Jumlah_Produksi_Cacat' => $sum->total
                    ]);
            }

            DB::commit();

            Log::info('Import multi-sheet berhasil. File: ' . $file->getClientOriginalName());
            return redirect()->back()->with('success', 'Data berhasil diimport dan di-proses!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import gagal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}