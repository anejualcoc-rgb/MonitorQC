<?php

namespace App\Http\Controllers;
use App\Exports\FullDataExport;
use App\Exports\DataExportManager;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ExcelExportController extends Controller
{
    public function export()
    {
        return Excel::download(new FullDataExport, 'Data-Produksi.xlsx');
    }


    public function index()
    {
        return view('report_manager');
    }

        public function index_spv()
    {
        return view('report_spv');
    }

    public function export_manager(Request $request)
    {
        $type = $request->input('type');
        $filterData = [];
        $fileName = 'Laporan-Produksi';

        // Logika penamaan file dan pengambilan data filter
        switch ($type) {
            case 'daily':
                $filterData = ['date' => $request->input('date')];
                $fileName .= '-Harian-' . $filterData['date'];
                break;
            case 'monthly':
                // Input month biasanya format "YYYY-MM"
                $filterData = ['month' => $request->input('month')]; 
                $fileName .= '-Bulanan-' . $filterData['month'];
                break;
            case 'yearly':
                $filterData = ['year' => $request->input('year')];
                $fileName .= '-Tahunan-' . $filterData['year'];
                break;
            default:
                $fileName .= '-Full';
                break;
        }

        $fileName .= '.xlsx';

        // Kirim $type dan $filterData ke Class Export Utama
        return Excel::download(new DataExportManager($type, $filterData), $fileName);
    }


    public function export_spv(Request $request)
    {
        $type = $request->input('type');
        $filterData = [];
        $fileName = 'Laporan-Produksi';

        // Logika penamaan file dan pengambilan data filter
        switch ($type) {
            case 'daily':
                $filterData = ['date' => $request->input('date')];
                $fileName .= '-Harian-' . $filterData['date'];
                break;
            case 'monthly':
                // Input month biasanya format "YYYY-MM"
                $filterData = ['month' => $request->input('month')]; 
                $fileName .= '-Bulanan-' . $filterData['month'];
                break;
            case 'yearly':
                $filterData = ['year' => $request->input('year')];
                $fileName .= '-Tahunan-' . $filterData['year'];
                break;
            default:
                $fileName .= '-Full';
                break;
        }

        $fileName .= '.xlsx';

        // Kirim $type dan $filterData ke Class Export Utama
        return Excel::download(new DataExportManager($type, $filterData), $fileName);
    }
}