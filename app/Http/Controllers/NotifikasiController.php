<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar Notifikasi user
     */
    public function index()
    {
        // Pastikan user login
        $userId = Auth::id();

        $Notifikasis = Notifikasi::where('user_id', $userId)
            ->orderBy('is_read', 'asc')       // Tampilkan yang belum dibaca dulu
            ->orderBy('created_at', 'desc')   // Kemudian urutkan dari yang terbaru
            ->paginate(10);                   // Batasi 10 per halaman

        return view('Notifikasi', compact('Notifikasis'));
    }

    /**
     * (Opsional) Menandai Notifikasi sudah dibaca
     */
    public function markAsRead($id)
    {
        $Notifikasi = Notifikasi::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $Notifikasi->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}