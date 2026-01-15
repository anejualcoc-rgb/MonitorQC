<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduksi;
use App\Models\TempDataProduksi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingData = TempDataProduksi::where('status_approval', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('approval.index', compact('pendingData'));
    }

    public function preview($id)
    {
        $tempData = TempDataProduksi::findOrFail($id);

        // Validasi: Jika data sudah tidak pending, jangan kasih akses edit/approve lagi
        if ($tempData->status_approval !== 'pending') {
            return redirect()->route('dashboard_spv')
                ->with('error', 'Data ini sudah diproses (Status: ' . $tempData->status_approval . ').');
        }

        return view('approval.preview', compact('tempData'));
    }

    // Aksi Menyetujui Data
public function approve($id)
    {
        $tempData = TempDataProduksi::findOrFail($id);

        // Cek status agar tidak double approve
        if ($tempData->status_approval !== 'pending') {
            return redirect()->route('dashboard_spv')->with('warning', 'Data sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($tempData) {
            // 1. Pindahkan data ke Tabel Asli (DataProduksi)
            $newData = DataProduksi::create([
                'User'                  => $tempData->User, 
                'Tanggal_Produksi'      => $tempData->Tanggal_Produksi,
                'Shift_Produksi'        => $tempData->Shift_Produksi,
                'Line_Produksi'         => $tempData->Line_Produksi,
                'Jumlah_Produksi'       => $tempData->Jumlah_Produksi,
                'Target_Produksi'       => $tempData->Target_Produksi,
                'Jumlah_Produksi_Cacat' => $tempData->Jumlah_Produksi_Cacat,
            ]);

            // 2. Update Status Temp Data
            $tempData->update(['status_approval' => 'approved']);

            // 3. Update Notifikasi SPV yang sedang login (Tandai sudah dibaca)
            Notifikasi::where('reference_id', $tempData->id)
                      ->where('user_id', auth()->id()) 
                      ->update(['is_read' => true]);
            
            // ============================================================
            // 4. LOGIKA NOTIFIKASI BARU (BERDASARKAN ROLE)
            // ============================================================

            // A. Kirim Notifikasi KHUSUS ke Staff Penginput (Personal)
            Notifikasi::create([
                'user_id' => $tempData->input_by_user_id,
                'judul'   => 'Data Produksi Disetujui',
                'pesan'   => 'Data Line ' . $tempData->Line_Produksi . ' (' . $tempData->Tanggal_Produksi . ') Anda telah disetujui.',
                'tipe'    => 'info',
                'is_read' => false
            ]);

            // B. Kirim Notifikasi UMUM ke Semua MANAGER (dan SPV lain)
            // Cari semua user yang jabatannya 'manager' (bisa ditambah 'spv' jika mau SPV lain juga tau)
            $recipients = \App\Models\User::whereIn('role', ['manager']) 
                            ->where('id', '!=', auth()->id()) // Jangan kirim ke diri sendiri (SPV yg approve)
                            ->where('id', '!=', $tempData->input_by_user_id) // Jangan kirim ke staff penginput (sudah dapat notif A)
                            ->get();

            foreach ($recipients as $recipient) {
                Notifikasi::create([
                    'user_id' => $recipient->id, // ID Manager A, Manager B, dst...
                    'judul'   => 'Laporan Produksi Baru ',
                    'pesan'   => 'Laporan resmi Line ' . $tempData->Line_Produksi . ' baru saja diterbitkan.',
                    'tipe'    => 'info', 
                    'data'    => [
                        'action_url' => route('produksi.show', $newData->id) 
                    ],
                    'is_read' => false
                ]);
            }
        });

        return redirect()->route('dashboard_spv')->with('success', 'Data berhasil disetujui. Notifikasi telah dikirim ke Staff dan seluruh Manager.');
    }
 
    
    // Aksi Menolak Data
    public function reject(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string|max:255']);
        
        $tempData = TempDataProduksi::findOrFail($id);

        if ($tempData->status_approval !== 'pending') {
            return redirect()->route('dashboard_spv')->with('warning', 'Data sudah diproses sebelumnya.');
        }

        // 1. Update Status jadi Rejected (JANGAN DI DELETE)
        $tempData->update([
            'status_approval' => 'rejected',
            // Pastikan di tabel temp_data_produksi ada kolom 'catatan_reject' (opsional)
            // 'catatan_reject' => $request->alasan 
        ]);

        // 2. Tandai Notifikasi SPV sebagai terbaca
        Notifikasi::where('reference_id', $tempData->id)->update(['is_read' => true]);

        // 3. Kirim Notifikasi ke Staff QC untuk Revisi
        Notifikasi::create([
            'user_id'      => $tempData->input_by_user_id,
            'judul'        => 'Data Produksi Ditolak',
            'pesan'        => 'Data Line ' . $tempData->Line_Produksi . ' ditolak. Alasan: ' . $request->alasan,
            'tipe'         => 'alert', // Warna merah
            
            // Kita hubungkan lagi ke data temp yang sama agar staff bisa klik dan edit
            'reference_id' => $tempData->id, 
            'data'         => [
                // Arahkan ke halaman edit ulang bagi staff
                'action_url' => route('datainput.edit', $tempData->id) 
            ],
            'is_read'      => false
        ]);

        return redirect()->route('dashboard_spv')->with('success', 'Data telah ditolak dan dikembalikan ke staff.');
    }
}