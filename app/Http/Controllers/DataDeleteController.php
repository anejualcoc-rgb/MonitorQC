<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataDeleteController extends Controller
{
    public function deleteAll()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('data_defect')->truncate();
            DB::table('data_produksi')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Semua data berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
