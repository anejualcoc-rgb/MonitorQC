<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TempDataProduksi; 
use App\Models\Notifikasi;      
use App\Models\User;           

class ExcelEditController extends Controller
{
    public function create()
    {
        return view('excel-edit');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'User'                  => 'required',
            'Tanggal_Produksi'      => 'required|date',
            'Shift_Produksi'        => 'required',
            'Line_Produksi'         => 'required',
            'Jumlah_Produksi'       => 'required|integer',
            'Target_Produksi'       => 'required|integer',
            'Jumlah_Produksi_Cacat' => 'nullable|integer', 
        ]);


        $tempData = TempDataProduksi::create([
            'User'                  => $request->User,
            'Tanggal_Produksi'      => $request->Tanggal_Produksi,
            'Shift_Produksi'        => $request->Shift_Produksi,
            'Line_Produksi'         => $request->Line_Produksi,
            'Jumlah_Produksi'       => $request->Jumlah_Produksi,
            'Target_Produksi'       => $request->Target_Produksi,
            
            'Jumlah_Produksi_Cacat' => $request->input('Jumlah_Produksi_Cacat', 0),
            

            'input_by_user_id'      => Auth::id(), 
            'status_approval'       => 'pending',  
        ]);

        $recipients = User::where('role', 'spv')->get(); 


        foreach ($recipients as $spv) {
            Notifikasi::create([
                'user_id'      => $spv->id, 
                'judul'        => 'Persetujuan Data Produksi',
                'pesan'        => 'Staff ' . Auth::user()->name . ' menginput data produksi Line ' . $request->Line_Produksi,
                'tipe'         => 'approval',
                'reference_id' => $tempData->id, 
            
                'data'         => [
                    'action_url' => route('approval.preview', $tempData->id) 
                ],
                'is_read'      => false
            ]);
        }
        return redirect()->route('datainput')->with('success', '⏳ Data berhasil disimpan dan notifikasi telah dikirim ke SPV QC!');
    }
}