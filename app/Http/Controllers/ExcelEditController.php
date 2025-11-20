<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduksi;


class ExcelEditController extends Controller
{
    public function create()
    {
        return view('excel-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'User' => 'required',
            'Tanggal_Produksi' => 'required|date',
            'Shift_Produksi' => 'required',
            'Line_Produksi' => 'required',
            'Jumlah_Produksi' => 'required|integer',
            'Target_Produksi' => 'required|integer',
        ]);

        DataProduksi::create([
            'User' => $request->User,
            'Tanggal_Produksi' => $request->Tanggal_Produksi,
            'Shift_Produksi' => $request->Shift_Produksi,
            'Line_Produksi' => $request->Line_Produksi,
            'Jumlah_Produksi' => $request->Jumlah_Produksi,
            'Target_Produksi' => $request->Target_Produksi,
        ]);

        return redirect()->route('datainput')->with('success', '✅ Data berhasil disimpan!');
    }
}
