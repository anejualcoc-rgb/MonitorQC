<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TempDataProduksiSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada user dengan ID 1 atau 2 di tabel users sebelumnya
        // atau sesuaikan 'input_by_user_id' dengan id user yang ada.
        
        $data = [
            // Skenario 1: Data Bagus (Achievement Tinggi, Defect Rendah) - PENDING
            [
                'Tanggal_Produksi'      => Carbon::now()->format('Y-m-d'),
                'Line_Produksi'         => 'Line 1',
                'Shift_Produksi'        => '1',
                'Jumlah_Produksi'       => 1050, // Melebihi target
                'Target_Produksi'       => 1000,
                'Jumlah_Produksi_Cacat' => 5,    // Defect sangat kecil
                'input_by_user_id'      => 1,    // Asumsi ID staff
                'status_approval'       => 'pending',
                'catatan_revisi'        => null,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            // Skenario 2: Data Bermasalah (Defect Tinggi > 2%) - PENDING
            [
                'Tanggal_Produksi'      => Carbon::now()->format('Y-m-d'),
                'Line_Produksi'         => 'Line 2',
                'Shift_Produksi'        => '2',
                'Jumlah_Produksi'       => 900,
                'Target_Produksi'       => 1000,
                'Jumlah_Produksi_Cacat' => 45,   // Defect 5% (Tinggi) -> Kandidat Reject
                'input_by_user_id'      => 1,
                'status_approval'       => 'pending',
                'catatan_revisi'        => null,
                'created_at'            => Carbon::now()->subHours(2),
                'updated_at'            => Carbon::now()->subHours(2),
            ],
            // Skenario 3: Data Kurang Target (Mesin Rusak dll) - PENDING
            [
                'Tanggal_Produksi'      => Carbon::yesterday()->format('Y-m-d'),
                'Line_Produksi'         => 'Line 3',
                'Shift_Produksi'        => '3',
                'Jumlah_Produksi'       => 500,  // Jauh di bawah target
                'Target_Produksi'       => 1000,
                'Jumlah_Produksi_Cacat' => 2,
                'input_by_user_id'      => 2,
                'status_approval'       => 'pending',
                'catatan_revisi'        => null,
                'created_at'            => Carbon::yesterday(),
                'updated_at'            => Carbon::yesterday(),
            ],
            // Skenario 4: Data yang sudah pernah DITOLAK (History)
            [
                'Tanggal_Produksi'      => Carbon::now()->subDays(2)->format('Y-m-d'),
                'Line_Produksi'         => 'Line 1',
                'Shift_Produksi'        => '1',
                'Jumlah_Produksi'       => 1200,
                'Target_Produksi'       => 1000,
                'Jumlah_Produksi_Cacat' => 0,
                'input_by_user_id'      => 1,
                'status_approval'       => 'rejected',
                'catatan_revisi'        => 'Jumlah produksi tidak masuk akal, mohon cek ulang laporan fisik.',
                'created_at'            => Carbon::now()->subDays(2),
                'updated_at'            => Carbon::now()->subDays(2),
            ],
        ];

        DB::table('temp_data_produksi')->insert($data);
    }
}