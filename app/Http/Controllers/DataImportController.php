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
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
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

            // Deteksi Format
            $isFormatB = false;
            foreach ($sheetNames as $name) {
                if (strtolower(trim($name)) === 'produksi') {
                    $isFormatB = true;
                    break;
                }
            }

            if ($isFormatB) {
                $this->processFormatB($spreadsheet, $sheetNames);
            } else {
                $this->processFormatA($spreadsheet);
            }

            $this->recalculateDefectTotals();

            DB::commit();
            Log::info('Import berhasil.');
            return redirect()->back()->with('success', 'Data berhasil diimport!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // FORMAT A (EKA/Laporan Bulanan)
    // Struktur Baru: 
    // No(0) | Date(1) | Shift(2) | Line(3) | ITEM/STYLE(4) | Target(5) | Actual(6) | User(7)
    // =========================================================================
    protected function processFormatA($spreadsheet)
    {
        // 1. Sheet Produksi
        $sheetProd = $spreadsheet->getSheet(0);
        $rowsProd = $sheetProd->toArray(null, true, true, false);

        for ($r = 1; $r < count($rowsProd); $r++) { 
            $row = $rowsProd[$r];
            
            // Debugging: Cek baris pertama di Log Railway untuk melihat posisi kolom
            if ($r === 1) {
                Log::info('DEBUG ROW FORMAT A: ' . json_encode($row));
            }

            if (empty($row[1])) continue; // Pastikan ada tanggal

            // Mapping Baru (Digeser +1 dari index 4 ke atas)
            DataProduksi::create([
                'Tanggal_Produksi' => $this->parseDate($row[1]), // Index 1
                'Shift_Produksi'   => $row[2] ?? null,           // Index 2
                'Line_Produksi'    => $row[3] ?? null,           // Index 3
                // Index 4 kemungkinan Nama Barang (dilewati)
                'Target_Produksi'  => intval($row[5] ?? 0),      // Index 5 (Sebelumnya 4)
                'Jumlah_Produksi'  => intval($row[6] ?? 0),      // Index 6 (Sebelumnya 5)
                'User'             => $row[7] ?? null,           // Index 7 (Sebelumnya 6)
                'Jumlah_Produksi_Cacat' => 0,
            ]);
        }

        // 2. Sheet Defect
        if ($spreadsheet->getSheetCount() > 1) {
            $sheetDefect = $spreadsheet->getSheet(1);
            $rowsDefect = $sheetDefect->toArray(null, true, true, false);
            
            // Mapping Defect Format A (Tetap seperti sebelumnya jika tidak error)
            $this->processDefectRows($rowsDefect, [
                'date' => 1, 
                'item' => 2, 
                'type' => 3, 
                'amount' => 4, 
                'severity' => 5, 
                'line' => 6, 
                'shift' => 7
            ]);
        }
    }

    // =========================================================================
    // FORMAT B (Custom Import)
    // =========================================================================
    protected function processFormatB($spreadsheet, $sheetNames)
    {
        // Sheet Produksi
        foreach ($sheetNames as $index => $sheetName) {
            if (strtolower(trim($sheetName)) === 'produksi') {
                $sheet = $spreadsheet->getSheet($index);
                $rows = $sheet->toArray(null, true, true, false);

                for ($r = 1; $r < count($rows); $r++) {
                    $row = $rows[$r];
                    if (empty($row[1])) continue;

                    DataProduksi::create([
                        'User'             => $row[0] ?? null,
                        'Tanggal_Produksi' => $this->parseDate($row[1]),
                        'Shift_Produksi'   => $row[2] ?? null,
                        'Line_Produksi'    => $row[3] ?? null,
                        'Jumlah_Produksi'  => intval($row[4] ?? 0),
                        'Target_Produksi'  => intval($row[5] ?? 0),
                        'Jumlah_Produksi_Cacat' => 0,
                    ]);
                }
            }
        }

        // Sheet Defects
        foreach ($sheetNames as $index => $sheetName) {
            if (stripos($sheetName, 'defect') !== false) {
                $sheet = $spreadsheet->getSheet($index);
                $rows = $sheet->toArray(null, true, true, false);
                
                $this->processDefectRows($rows, [
                    'date' => 0, 'item' => 1, 'type' => 2, 'amount' => 3, 
                    'severity' => 4, 'line' => 5, 'shift' => 6
                ]);
            }
        }
    }

    // =========================================================================
    // Shared Logic
    // =========================================================================
    protected function processDefectRows($rows, $mapping)
    {
        for ($r = 1; $r < count($rows); $r++) {
            $row = $rows[$r];
            
            if (empty($row[$mapping['date']]) && empty($row[$mapping['type']])) continue;

            $tanggal = $this->parseDate($row[$mapping['date']] ?? null);
            $line = $row[$mapping['line']] ?? null;
            $shift = $row[$mapping['shift']] ?? null;

            $produksi = null;
            if ($tanggal) {
                $query = DataProduksi::whereDate('Tanggal_Produksi', $tanggal);
                if ($line) $query->where('Line_Produksi', $line);
                if ($shift) $query->where('Shift_Produksi', $shift);
                $produksi = $query->first();

                if (!$produksi) {
                    $produksi = DataProduksi::whereDate('Tanggal_Produksi', $tanggal)->first();
                }
            }

            if (!$produksi) continue; 

            DataDefect::create([
                'data_produksi_id'      => $produksi->id,
                'Tanggal_Produksi'      => $tanggal,
                'Nama_Barang'           => $row[$mapping['item']] ?? null,
                'Jenis_Defect'          => $row[$mapping['type']] ?? null,
                'Jumlah_Cacat_perjenis' => intval($row[$mapping['amount']] ?? 0),
                'Severity'              => $row[$mapping['severity']] ?? null,
            ]);
        }
    }

    protected function recalculateDefectTotals()
    {
        $defectSums = DataDefect::select('data_produksi_id', DB::raw('SUM(Jumlah_Cacat_perjenis) as total'))
            ->whereNotNull('data_produksi_id')
            ->groupBy('data_produksi_id')
            ->get();

        foreach ($defectSums as $sum) {
            DataProduksi::where('id', $sum->data_produksi_id)
                ->update(['Jumlah_Produksi_Cacat' => $sum->total]);
        }
    }
}