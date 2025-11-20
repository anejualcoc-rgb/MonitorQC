<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataDefect;
use App\Models\DataProduksi;
use Illuminate\Support\Facades\DB;

class DefectController extends Controller
{

    public function data()
    {
        $produksiList = DataProduksi::all();
        return view('datainput', compact('produksiList'));
      
    }

    public function create()
    {
        $produksiList = DataProduksi::all();
        return view('defect-create', compact('produksiList'));
    }

        
    public function store(Request $request)
    {
        $request->validate([
            'data_produksi_id' => 'required|exists:data_produksi,id',
            'Tanggal_Produksi' => 'required|date',
            'Nama_Barang' => 'required|string',
            'Jenis_Defect' => 'required|string',
            'Jumlah_Cacat_perjenis' => 'required|integer',
            'Severity' => 'required|string',
        ]);
        
        $jenisDefect = $request->Jenis_Defect;
        if ($jenisDefect === 'Lainnya') {
            $jenisDefect = $request->Jenis_Defect_Lainnya;}

        $defect = DataDefect::create([
            'data_produksi_id' => $request->data_produksi_id,
            'Tanggal_Produksi' => $request->Tanggal_Produksi,
            'Nama_Barang' => $request->Nama_Barang,
            'Jenis_Defect' => $jenisDefect,
            'Jumlah_Cacat_perjenis' => $request->Jumlah_Cacat_perjenis,
            'Severity' => $request->Severity,
        ]);

        DB::table('data_produksi')
        ->where('id', $request->data_produksi_id)
        ->increment('Jumlah_Produksi_Cacat', $request->Jumlah_Cacat_perjenis);


        return redirect()->route('datainput')->with('success', '✅ Data defect berhasil disimpan!');
    }

}
