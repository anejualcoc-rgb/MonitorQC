<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ASUMSI: ID Manager yang sedang login adalah 1.
        // Ubah variabel ini sesuai ID user Anda di database.
        $userId = 1; 

        $notifications = [
            // ---------------------------------------------------------
            // SKENARIO 1: Notifikasi APPROVAL (Belum Dibaca & Ada Link)
            // ---------------------------------------------------------
            [
                'user_id'   => $userId,
                'judul'     => 'Persetujuan Data Produksi',
                'pesan'     => 'Staff QC (Budi) menginput data produksi Line 1. Mohon ditinjau segera.',
                'tipe'      => 'approval', // Tipe ini mentrigger icon biru & tombol di view
                'data'      => json_encode([
                    'temp_id'    => 1, // ID dummy dari temp_data_produksi
                    // URL ini yang akan membuat judul/card bisa diklik
                    // Pastikan route 'approval.preview' sudah ada atau ganti dengan url manual
                    'action_url' => url('/approval/preview/1') 
                ]),
                'is_read'   => false,
                'read_at'   => null,
                'created_at' => Carbon::now()->subMinutes(5),
                'updated_at' => Carbon::now()->subMinutes(5),
            ],

            // ---------------------------------------------------------
            // SKENARIO 2: Notifikasi DEFECT TINGGI (Belum Dibaca - Warning)
            // ---------------------------------------------------------
            [
                'user_id'   => $userId,
                'judul'     => 'Alert: Defect Rate Tinggi!',
                'pesan'     => 'Terdeteksi defect rate mencapai 5% di Line 2 pada jam 10:00.',
                'tipe'      => 'defect', // Tipe ini mentrigger icon merah
                'data'      => json_encode([
                    'line' => 'Line 2',
                    'rate' => '5%'
                ]),
                'is_read'   => false,
                'read_at'   => null,
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],

            // ---------------------------------------------------------
            // SKENARIO 3: Notifikasi SISTEM (Sudah Dibaca)
            // ---------------------------------------------------------
            [
                'user_id'   => $userId,
                'judul'     => 'Maintenance Server Selesai',
                'pesan'     => 'Pemeliharaan sistem mingguan telah selesai. Sistem berjalan normal.',
                'tipe'      => 'sistem', // Tipe icon abu-abu/standar
                'data'      => null, // Tidak ada data tambahan
                'is_read'   => true, // Sudah dibaca (background putih)
                'read_at'   => Carbon::yesterday(),
                'created_at' => Carbon::yesterday()->addHours(2),
                'updated_at' => Carbon::yesterday()->addHours(2),
            ],

            // ---------------------------------------------------------
            // SKENARIO 4: Notifikasi QC Info (Belum Dibaca)
            // ---------------------------------------------------------
            [
                'user_id'   => $userId,
                'judul'     => 'Laporan Harian QC Tersedia',
                'pesan'     => 'Rekapitulasi laporan QC tanggal kemarin sudah siap diunduh.',
                'tipe'      => 'qc', // Tipe icon check/biru muda
                'data'      => null,
                'is_read'   => false,
                'read_at'   => null,
                'created_at' => Carbon::now()->subHours(4),
                'updated_at' => Carbon::now()->subHours(4),
            ],
            
            // ---------------------------------------------------------
            // SKENARIO 5: Approval Lain (Sudah Disetujui/Dibaca)
            // ---------------------------------------------------------
            [
                'user_id'   => $userId,
                'judul'     => 'Persetujuan Data Produksi',
                'pesan'     => 'Staff QC (Siti) menginput data produksi Line 3.',
                'tipe'      => 'approval',
                'data'      => json_encode([
                    'temp_id'    => 2,
                    'action_url' => url('/approval/preview/2')
                ]),
                'is_read'   => true, // Sudah dibaca
                'read_at'   => Carbon::now()->subHour(),
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
        ];

        DB::table('notifikasi')->insert($notifications);
    }
}