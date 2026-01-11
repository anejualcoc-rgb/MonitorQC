<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TempDataProduksi;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; 


        $tempData1 = TempDataProduksi::create([
            'User'                  => 'Budi Santoso',
            'Tanggal_Produksi'      => Carbon::now()->format('Y-m-d'),
            'Shift_Produksi'        => 'Pagi',
            'Line_Produksi'         => 'Line 1',
            'Jumlah_Produksi'       => 1000,
            'Target_Produksi'       => 1200,
            'Jumlah_Produksi_Cacat' => 5,
            'input_by_user_id'      => 2, 
            'status_approval'       => 'pending',
        ]);

        $tempData2 = TempDataProduksi::create([
            'User'                  => 'Siti Aminah', 
            'Tanggal_Produksi'      => Carbon::yesterday()->format('Y-m-d'),
            'Shift_Produksi'        => 'Siang',
            'Line_Produksi'         => 'Line 3',
            'Jumlah_Produksi'       => 950,
            'Target_Produksi'       => 1000,
            'Jumlah_Produksi_Cacat' => 2,
            'input_by_user_id'      => 3, 
            'status_approval'       => 'approved', 
        ]);

        // 2. Insert Notifikasi (Sama seperti sebelumnya)
        $notifications = [
            [
                'user_id'      => $userId,
                'judul'        => 'Persetujuan Data Produksi',
                'pesan'        => 'Staff QC (Budi) menginput data produksi Line 1. Mohon ditinjau segera.',
                'tipe'         => 'approval', 
                'reference_id' => $tempData1->id, 
                'data'         => json_encode(['action_url' => url('/approval/preview/' . $tempData1->id)]), // Pakai url() agar aman jika route belum ada
                'is_read'      => false,
                'created_at'   => Carbon::now()->subMinutes(5),
                'updated_at'   => Carbon::now()->subMinutes(5),
            ],
            // ... (lanjutkan item notifikasi lainnya seperti kode sebelumnya)
            // ... (pastikan item notifikasi approval ke-2 menggunakan reference_id => $tempData2->id)
        ];

        // Tips: Masukkan semua array notifikasi lainnya di sini (Defect, Sistem, QC Info)
        // Saya persingkat agar fokus ke perbaikan error 'User'

        DB::table('notifikasi')->insert($notifications);
    }
}