<?php

namespace App\Http\Controllers;
use App\Exports\FullDataExport;
use Maatwebsite\Excel\Facades\Excel;


class ExcelExportController extends Controller
{
    public function export()
    {
        return Excel::download(new FullDataExport, 'Data-Produksi.xlsx');
    }
}