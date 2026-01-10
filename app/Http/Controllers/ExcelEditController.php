<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TempDataProduksi; // Model tabel sementara
use App\Models\Notifikasi;      // Model notifikasi
use App\Models\User;            // Model User untuk cari manager

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
            'User' => 'required',
            'Tanggal_Produksi' => 'required|date',
            'Shift_Produksi' => 'required',
            'Line_Produksi' => 'required',
            'Jumlah_Produksi' => 'required|integer',
            'Target_Produksi' => 'required|integer',
            // Tambahkan validasi defect jika ada di form input
        ]);

        // 2. Simpan ke Tabel SEMENTARA (TempDataProduksi)
        // Bukan ke DataProduksi langsung
        $tempData = TempDataProduksi::create([
            'User' => $request->User,
            'Tanggal_Produksi' => $request->Tanggal_Produksi,
            'Shift_Produksi'   => $request->Shift_Produksi,
            'Line_Produksi'    => $request->Line_Produksi,
            'Jumlah_Produksi'  => $request->Jumlah_Produksi,
            'Target_Produksi'  => $request->Target_Produksi,
            
            // Kolom Default defect (jika tidak diinput form, set 0)
            'Jumlah_Produksi_Cacat' => $request->input('Jumlah_Produksi_Cacat', 0),
            
            // Kolom Sistem Approval
            'input_by_user_id' => Auth::id(), 
            'status_approval'  => 'pending',  
        ]);

        // 3. Kirim Notifikasi ke Manager
        // Logika: Cari user yang jabatannya Manager (sesuaikan dengan kolom di tabel users Anda)
        // Contoh: where('role', 'manager') atau where('email', 'manager@ptxyz.com')
        $managers = User::where('role', 'manager')->get(); 

        foreach ($managers as $manager) {
            Notifikasi::create([
                'user_id' => $manager->id,
                'judul'   => 'Persetujuan Data Produksi',
                'pesan'   => 'Staff ' . Auth::user()->name . ' menginput data produksi Line ' . $request->Line_Produksi,
                'tipe'    => 'approval',
                'data'    => [
                    'temp_id' => $tempData->id, 
                    'action_url' => route('approval.preview', $tempData->id) 
                ],
                'is_read' => false
            ]);
        }

        // 4. Redirect dengan Pesan Pending
        return redirect()->route('datainput')->with('success', '⏳ Data berhasil disimpan dan menunggu persetujuan Manager!');
    }
}