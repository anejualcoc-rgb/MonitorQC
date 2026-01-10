<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar Notification user
     */
    public function index()
    {
        // Pastikan user login
        $userId = Auth::id();

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('is_read', 'asc')       // Tampilkan yang belum dibaca dulu
            ->orderBy('created_at', 'desc')   // Kemudian urutkan dari yang terbaru
            ->paginate(10);                   // Batasi 10 per halaman

        return view('notification', compact('notifications'));
    }

    /**
     * (Opsional) Menandai Notification sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                        ->where('id', $id)
                        ->firstOrFail();

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notification ditandai sudah dibaca.');
    }
}