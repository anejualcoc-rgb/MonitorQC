@extends('layouts.app_spv') 

@section('title', 'Daftar Persetujuan')

@section('content')

<style>
    .approval-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }
    
    .status-badge-pending {
        background-color: #fff7ed;
        color: #c2410c;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid #ffedd5;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-review {
        background-color: #015255;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-review:hover {
        background-color: #013d3f;
        color: white;
        transform: translateY(-1px);
    }

    .table-custom thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
    }

    .user-avatar-sm {
        width: 32px;
        height: 32px;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
        margin-right: 8px;
    }
</style>

<div class="container-fluid px-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Daftar Persetujuan</h2>
            <p class="text-secondary mb-0">Menampilkan data produksi yang menunggu konfirmasi</p>
        </div>
        <div class="badge bg-warning text-dark px-3 py-2 rounded-pill">
            <i class="bi bi-hourglass-split"></i> {{ $pendingData->total() }} Pending
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="approval-card">
        <div class="table-responsive">
            <table class="table table-custom mb-0 table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Staff Penginput</th>
                        <th width="15%">Tanggal Produksi</th>
                        <th width="15%">Detail Line</th>
                        <th width="15%">Waktu Input</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingData as $index => $item)
                        <tr>
                            <td>{{ $pendingData->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm">
                                        {{ substr($item->User, 0, 1) }}
                                    </div>
                                    <span class="fw-medium">{{ $item->User }}</span>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                {{ \Carbon\Carbon::parse($item->Tanggal_Produksi)->format('d M Y') }}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary">{{ $item->Line_Produksi }}</span>
                                    <small class="text-muted">Shift {{ $item->Shift_Produksi }}</small>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $item->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <span class="status-badge-pending">
                                    <i class="bi bi-clock"></i> Menunggu
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('approval.preview', $item->id) }}" class="btn-action-review">
                                    <i class="bi bi-eye"></i> Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No Data" style="width: 80px; opacity: 0.5;" class="mb-3">
                                    <h5 class="text-secondary fw-bold">Tidak ada data pending</h5>
                                    <p class="text-muted">Semua laporan produksi telah diproses.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pendingData->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $pendingData->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@endsection