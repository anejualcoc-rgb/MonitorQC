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
            
            Notifikasi::create([
                'user_id' => $tempData->input_by_user_id,
                'judul'   => 'Data Produksi Disetujui',
                'pesan'   => 'Data Line ' . $tempData->Line_Produksi . ' (' . $tempData->Tanggal_Produksi . ') Anda telah disetujui.',
                'tipe'    => 'info',
                'is_read' => false
            ]);

            $recipients = \App\Models\User::whereIn('role', ['manager']) 
                            ->where('id', '!=', auth()->id()) 
                            ->where('id', '!=', $tempData->input_by_user_id) 
                            ->get();

            foreach ($recipients as $recipient) {
                Notifikasi::create([
                    'user_id' => $recipient->id, 
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
 
    
    public function reject(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string|max:255']);
        
        $tempData = TempDataProduksi::findOrFail($id);

        if ($tempData->status_approval !== 'pending') {
            return redirect()->route('dashboard_spv')->with('warning', 'Data sudah diproses sebelumnya.');
        }

        $tempData->update([
            'status_approval' => 'rejected',
        ]);

        Notifikasi::where('reference_id', $tempData->id)->update(['is_read' => true]);

        Notifikasi::create([
            'user_id'      => $tempData->input_by_user_id,
            'judul'        => 'Data Produksi Ditolak',
            'pesan'        => 'Data Line ' . $tempData->Line_Produksi . ' ditolak. Alasan: ' . $request->alasan,
            'tipe'         => 'alert', 

            'reference_id' => $tempData->id, 
            'data'         => [
                'action_url' => route('datainput.edit', $tempData->id) 
            ],
            'is_read'      => false
        ]);

        return redirect()->route('dashboard_spv')->with('success', 'Data telah ditolak dan dikembalikan ke staff.');
    }
}