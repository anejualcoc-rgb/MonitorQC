@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - QC Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Inter', sans-serif; }
        
        .main-header {
            background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(1, 82, 85, 0.15);
        }

        .notif-card {
            border: none;
            border-radius: 12px;
            background: white;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            border-left: 5px solid transparent;
        }

        /* Styling khusus notifikasi belum dibaca */
        .notif-card.unread {
            background-color: #f0fdfa; /* Hijau pudar sangat muda */
            border-left-color: #015255; /* Garis indikator teal */
        }
        
        .notif-card.unread .notif-title {
            font-weight: 700;
            color: #015255;
        }

        .notif-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        /* Warna icon berdasarkan tipe */
        .bg-icon-qc { background-color: #e0f2fe; color: #0284c7; } /* Biru */
        .bg-icon-defect { background-color: #fee2e2; color: #dc2626; } /* Merah */
        .bg-icon-system { background-color: #f3f4f6; color: #4b5563; } /* Abu */

        .notif-content { flex: 1; }
        .notif-title { font-size: 1rem; margin-bottom: 5px; color: #333; }
        .notif-desc { font-size: 0.9rem; color: #666; margin-bottom: 5px; }
        .notif-time { font-size: 0.75rem; color: #999; }
        
        .btn-mark-read {
            font-size: 0.8rem;
            color: #015255;
            text-decoration: none;
        }
        .btn-mark-read:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/dashboard') }}" class="text-white fs-4"><i class="bi bi-arrow-left"></i></a>
                <h4 class="mb-0">Notifikasi Saya</h4>
            </div>
            <span class="badge bg-white text-teal rounded-pill px-3 py-2" style="color: #015255;">
                {{ $notifications->where('is_read', false)->count() }} Belum Dibaca
            </span>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @forelse($notifications as $notif)
                    <div class="notif-card p-3 d-flex align-items-start {{ $notif->is_read ? '' : 'unread' }}">
                        
                        <div class="icon-box 
                            @if($notif->tipe == 'defect') bg-icon-defect 
                            @elseif($notif->tipe == 'qc') bg-icon-qc 
                            @else bg-icon-system @endif">
                            
                            @if($notif->tipe == 'defect') <i class="bi bi-exclamation-triangle-fill"></i>
                            @elseif($notif->tipe == 'qc') <i class="bi bi-clipboard-check-fill"></i>
                            @else <i class="bi bi-bell-fill"></i> @endif
                        </div>

                        <div class="notif-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="notif-title">{{ $notif->judul }}</h5>
                                
                                @if(!$notif->is_read)
                                    <span class="badge bg-danger" style="font-size: 0.6rem;">BARU</span>
                                @endif
                            </div>
                            
                            <p class="notif-desc">{{ $notif->pesan }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="notif-time">
                                    <i class="bi bi-clock"></i> {{ $notif->created_at->diffForHumans() }}
                                </span>

                                @if(!$notif->is_read)
                                    <form action="{{ route('notification.read', $notif->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-link p-0 btn-mark-read">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success" style="font-size: 0.8rem;">
                                        <i class="bi bi-check-all"></i> Dibaca
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" width="120" style="opacity: 0.5; margin-bottom: 20px;">
                        <h5 class="text-muted">Tidak ada notifikasi saat ini</h5>
                        <p class="text-muted small">Semua informasi terbaru akan muncul di sini.</p>
                    </div>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection