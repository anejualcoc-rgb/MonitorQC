<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduksi;
use App\Models\TempDataProduksi;
use App\Models\Notifikasi;

class ApprovalController extends Controller
{
    // Menampilkan detail data pending sebelum diapprove
    public function preview($id)
    {
        $tempData = TempDataProduksi::findOrFail($id);
        return view('approval.preview', compact('tempData'));
    }

    // Aksi Menyetujui Data
    public function approve($id)
    {
        $tempData = TempDataProduksi::findOrFail($id);

        // 1. Pindahkan data dari Temp ke Tabel Asli (DataProduksi)
        DataProduksi::create([
            'Tanggal_Produksi' => $tempData->Tanggal_Produksi,
            'Line_Produksi'    => $tempData->Line_Produksi,
            'Shift_Produksi'   => $tempData->Shift_Produksi,
            'Jumlah_Produksi'  => $tempData->Jumlah_Produksi,
            'Target_Produksi'  => $tempData->Target_Produksi,
            'Jumlah_Produksi_Cacat' => $tempData->Jumlah_Produksi_Cacat,
            // Field lain sesuaikan...
        ]);

        // 2. Kirim Notifikasi Balik ke Staff QC
        Notifikasi::create([
            'user_id' => $tempData->input_by_user_id,
            'judul'   => 'Data Produksi Disetujui',
            'pesan'   => 'Data produksi tanggal ' . $tempData->Tanggal_Produksi . ' telah disetujui Manager.',
            'tipe'    => 'sistem',
            'is_read' => false
        ]);

        // 3. Hapus data dari Temp (karena sudah masuk tabel asli)
        $tempData->delete();

        return redirect()->route('dashboard_spv')->with('success', 'Data berhasil disetujui dan masuk ke laporan utama.');
    }

    // Aksi Menolak Data
    public function reject(Request $request, $id)
    {
        $tempData = TempDataProduksi::findOrFail($id);
        $alasan = $request->input('alasan', 'Data tidak valid');

        // 1. Kirim Notifikasi Penolakan ke Staff QC
        Notifikasi::create([
            'user_id' => $tempData->input_by_user_id,
            'judul'   => 'Data Produksi Ditolak',
            'pesan'   => 'Data ditolak. Alasan: ' . $alasan,
            'tipe'    => 'alert', // Merah
            'data'    => ['old_data' => $tempData->toArray()], // Opsional: kembalikan data lama biar bisa diedit
            'is_read' => false
        ]);

        // 2. Hapus data temp (atau ubah status jadi 'rejected' jika ingin menyimpan history)
        $tempData->delete(); 

        return redirect()->route('dashboard_spv')->with('error', 'Data telah ditolak.');
    }
}