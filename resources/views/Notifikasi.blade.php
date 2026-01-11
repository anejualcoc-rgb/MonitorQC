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
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        
        /* ... (Style Header Sama Seperti Sebelumnya) ... */
        .main-header {
            background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white;
            padding: 24px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(1, 82, 85, 0.15);
        }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .back-btn { color: white; font-size: 1.5rem; text-decoration: none; transition: opacity 0.2s; }
        .back-btn:hover { opacity: 0.8; }
        .header-title { display: flex; align-items: center; gap: 15px; }
        .header-title h4 { margin: 0; font-weight: 600; font-size: 1.5rem; }
        .unread-badge {
            background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); color: white;
            padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.9rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Card Styles */
        .notif-card {
            background: white; border-radius: 12px; padding: 20px; margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s ease;
            border-left: 4px solid transparent; display: flex; gap: 16px;
        }
        .notif-card.unread {
            background: linear-gradient(to right, #f0fdfa 0%, #ffffff 100%);
            border-left-color: #015255;
            box-shadow: 0 2px 6px rgba(1, 82, 85, 0.12);
        }
        .notif-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }

        /* Icon Colors */
        .icon-box {
            width: 48px; height: 48px; border-radius: 12px; display: flex;
            align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;
        }
        .bg-icon-qc { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); color: #0284c7; }
        .bg-icon-defect { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; }
        .bg-icon-system { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #6b7280; }
        
        /* WARNA BARU UNTUK INFO/LAPORAN */
        .bg-icon-info { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #059669; }

        /* Content & Typography */
        .notif-content { flex: 1; min-width: 0; }
        .notif-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px; gap: 12px; }
        .notif-title { font-size: 1.05rem; font-weight: 600; color: #1f2937; margin: 0; line-height: 1.4; }
        .notif-card.unread .notif-title { color: #015255; font-weight: 700; }
        .badge-new {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white;
            padding: 3px 10px; border-radius: 12px; font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.5px; white-space: nowrap;
        }
        .notif-desc { font-size: 0.95rem; color: #6b7280; margin-bottom: 12px; line-height: 1.5; }
        .notif-footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .notif-time { font-size: 0.8rem; color: #9ca3af; display: flex; align-items: center; gap: 6px; }

        /* Buttons & Status */
        .btn-mark-read {
            background: transparent; border: 1px solid #015255; color: #015255;
            padding: 6px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        }
        .btn-mark-read:hover { background: #015255; color: white; transform: translateY(-1px); }
        
        .status-read { color: #10b981; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px; }

        .btn-review {
            background: #015255; color: white; padding: 6px 16px; border-radius: 8px;
            font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex;
            align-items: center; gap: 6px; border: none; transition: all 0.2s;
        }
        .btn-review:hover { background: #013d3f; color: white; transform: translateY(-1px); }

        /* Tombol Baru untuk Lihat Laporan */
        .btn-report {
            background: white; border: 1px solid #015255; color: #015255;
            padding: 6px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;
        }
        .btn-report:hover { background: #f0fdfa; color: #013d3f; }

        .badge-status {
            padding: 6px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px;
        }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state img { width: 140px; opacity: 0.4; margin-bottom: 24px; }
        .empty-state h5 { color: #6b7280; font-weight: 600; margin-bottom: 8px; }
        .empty-state p { color: #9ca3af; font-size: 0.95rem; }
        .pagination { margin-top: 30px; }

        @media (max-width: 768px) {
            .header-title h4 { font-size: 1.2rem; }
            .unread-badge { font-size: 0.8rem; padding: 6px 14px; }
            .notif-card { padding: 16px; }
            .icon-box { width: 42px; height: 42px; font-size: 1.1rem; }
            .notif-title { font-size: 1rem; }
            .notif-desc { font-size: 0.9rem; }
        }
    </style>
</head>
<body>

    <div class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <a href="{{ url('/dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h4>Notifikasi Saya</h4>
                </div>
                <div class="unread-badge">
                    <i class="bi bi-bell-fill"></i>
                    {{ $Notifikasis->where('is_read', false)->count() }} Belum Dibaca
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                
                @forelse($Notifikasis as $notif)
                    <div class="notif-card {{ $notif->is_read ? '' : 'unread' }}">
                        
                        <div class="icon-box 
                            @if($notif->tipe == 'defect') bg-icon-defect 
                            @elseif($notif->tipe == 'qc') bg-icon-qc 
                            @elseif($notif->tipe == 'approval') bg-icon-qc 
                            @elseif($notif->tipe == 'info') bg-icon-info {{-- Class Baru --}}
                            @else bg-icon-system @endif">
                            
                            @if($notif->tipe == 'defect') 
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            @elseif($notif->tipe == 'qc') 
                                <i class="bi bi-clipboard-check-fill"></i>
                            @elseif($notif->tipe == 'approval')
                                <i class="bi bi-check-circle-fill"></i>
                            @elseif($notif->tipe == 'info')
                                <i class="bi bi-file-earmark-text-fill"></i> {{-- Icon Laporan --}}
                            @else 
                                <i class="bi bi-bell-fill"></i> 
                            @endif
                        </div>

                        <div class="notif-content">
                            <div class="notif-header">
                                <h5 class="notif-title">{{ $notif->judul }}</h5>
                                @if(!$notif->is_read)
                                    <span class="badge-new">BARU</span>
                                @endif
                            </div>
                            
                            <p class="notif-desc">{{ $notif->pesan }}</p>
                            
                            <div class="notif-footer">
                                <span class="notif-time">
                                    <i class="bi bi-clock"></i> 
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>

                                {{-- --- LOGIKA TOMBOL (PENTING) --- --}}
                                
                                {{-- KASUS 1: Approval (Review Data Pending) --}}
                                @if($notif->tipe == 'approval' && isset($notif->data['action_url']))
                                    @if(optional($notif->tempDataProduksi)->status_approval == 'pending')
                                        <a href="{{ $notif->data['action_url'] }}" class="btn-review">
                                            <i class="bi bi-eye"></i> Tinjau Data
                                        </a>
                                    @elseif(optional($notif->tempDataProduksi)->status_approval == 'approved')
                                        <span class="badge-status bg-success bg-opacity-10 text-success border border-success">
                                            <i class="bi bi-check-circle-fill"></i> Disetujui
                                        </span>
                                    @elseif(optional($notif->tempDataProduksi)->status_approval == 'rejected')
                                        <span class="badge-status bg-danger bg-opacity-10 text-danger border border-danger">
                                            <i class="bi bi-x-circle-fill"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge-status bg-secondary bg-opacity-10 text-secondary">
                                            Data Tidak Tersedia
                                        </span>
                                    @endif

                                {{-- KASUS 2: Info (Link ke Detail Laporan Jadi) --}}
                                @elseif($notif->tipe == 'info' && isset($notif->data['action_url']))
                                    <a href="{{ $notif->data['action_url'] }}" class="btn-report">
                                        <i class="bi bi-file-text"></i> Lihat Laporan
                                    </a>
                                @endif
                                
                                {{-- ------------------------------------------------ --}}

                                {{-- Logic Tombol "Tandai Dibaca" --}}
                                @if(!$notif->is_read && $notif->tipe != 'approval' && $notif->tipe != 'info') 
                                    {{-- Untuk Approval & Info yang ada linknya, user biasanya klik link utama --}}
                                    <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-mark-read">
                                            <i class="bi bi-check2"></i> Tandai Dibaca
                                        </button>
                                    </form>
                                @elseif($notif->tipe != 'approval' && $notif->tipe != 'info')
                                    <span class="status-read">
                                        <i class="bi bi-check-circle-fill"></i> Sudah Dibaca
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Tidak ada notifikasi">
                        <h5>Tidak Ada Notifikasi</h5>
                        <p>Semua informasi dan pembaruan akan muncul di sini.</p>
                    </div>
                @endforelse

                <div class="d-flex justify-content-center">
                    {{ $Notifikasis->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection