@extends('layouts.app_spv') 

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinjau Persetujuan - QC Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Inter', sans-serif; }

        /* Header Gradient Teal */
        .approval-header {
            background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(1, 82, 85, 0.2);
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header-custom {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 20px;
            font-weight: 700;
            color: #015255;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .data-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .stat-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .stat-box.highlight {
            background-color: #e0f2fe; /* Biru muda */
            border-color: #bae6fd;
            color: #0284c7;
        }

        /* Tombol Aksi Floating di Bawah (Optional) atau dalam Card */
        .action-card {
            background: white;
            border-top: 1px solid #eee;
            padding: 20px;
            border-radius: 0 0 12px 12px;
        }

        .btn-action {
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            width: 100%;
        }
        
        .btn-approve {
            background-color: #059669;
            color: white;
            border: none;
        }
        .btn-approve:hover { background-color: #047857; color: white; transform: translateY(-2px); }

        .btn-reject {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .btn-reject:hover { background-color: #fecaca; transform: translateY(-2px); }

    </style>
</head>
<body>

    <div class="approval-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-clipboard-check"></i> Tinjau Data Produksi</h2>
                    <p class="mb-0 opacity-75">Permintaan persetujuan input data harian</p>
                </div>
                <a href="{{ route('dashboard_spv') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-person-circle"></i> Informasi Penginput
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="data-label">Nama Staff QC</div>
                                <div class="data-value">{{ $tempData->User }}</div> 
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="data-label">Waktu Input</div>
                                <div class="data-value">{{ $tempData->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Detail Data Produksi
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="data-label">Tanggal Produksi</div>
                                <div class="data-value">{{ \Carbon\Carbon::parse($tempData->Tanggal_Produksi)->format('d F Y') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="data-label">Shift</div>
                                <div class="data-value">Shift {{ $tempData->Shift_Produksi }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="data-label">Line Produksi</div>
                                <div class="data-value">{{ $tempData->Line_Produksi }}</div>
                            </div>

                            <div class="col-12"><hr class="my-2"></div>

                            <div class="col-md-4">
                                <div class="stat-box">
                                    <div class="data-label">Target</div>
                                    <div class="fs-4 fw-bold">{{ number_format($tempData->Target_Produksi) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box highlight">
                                    <div class="data-label text-primary">Aktual Output</div>
                                    <div class="fs-4 fw-bold text-primary">{{ number_format($tempData->Jumlah_Produksi) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box" style="background-color: #fee2e2; border-color: #fecaca;">
                                    <div class="data-label text-danger">Total Cacat</div>
                                    <div class="fs-4 fw-bold text-danger">{{ number_format($tempData->Jumlah_Produksi_Cacat) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                
                <div class="card card-custom mb-3">
                    <div class="card-body p-4 text-center">
                        <div class="data-label mb-2">Status Saat Ini</div>
                        @if($tempData->status_approval == 'pending')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split"></i> Menunggu Persetujuan</span>
                        @elseif($tempData->status_approval == 'approved')
                            <span class="badge bg-success fs-6 px-3 py-2 rounded-pill"><i class="bi bi-check-circle"></i> Disetujui</span>
                        @else
                            <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><i class="bi bi-x-circle"></i> Ditolak</span>
                        @endif
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-calculator"></i> Analisis Sistem
                    </div>
                    <div class="card-body p-4">
                        @php
                            $achievement = $tempData->Target_Produksi > 0 ? ($tempData->Jumlah_Produksi / $tempData->Target_Produksi) * 100 : 0;
                            $defectRate = $tempData->Jumlah_Produksi > 0 ? ($tempData->Jumlah_Produksi_Cacat / $tempData->Jumlah_Produksi) * 100 : 0;
                        @endphp

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-secondary">Pencapaian Target</span>
                                <span class="{{ $achievement >= 100 ? 'text-success' : 'text-warning' }} fw-bold">{{ number_format($achievement, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $achievement >= 100 ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ min($achievement, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-secondary">Defect Rate</span>
                                <span class="{{ $defectRate > 2 ? 'text-danger' : 'text-success' }} fw-bold">{{ number_format($defectRate, 2) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $defectRate > 2 ? 'bg-danger' : 'bg-success' }}" role="progressbar" style="width: {{ min($defectRate * 10, 100) }}%"></div>
                            </div>
                            @if($defectRate > 2)
                                <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-triangle"></i> Rate di atas batas wajar (2%)</small>
                            @endif
                        </div>
                    </div>

                    @if($tempData->status_approval == 'pending')
                    <div class="action-card">
                        <div class="d-grid gap-2">
                            <form action="{{ route('approval.approve', $tempData->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-action btn-approve" onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
                                    <i class="bi bi-check-lg"></i> Setujui Data
                                </button>
                            </form>

                            <button type="button" class="btn btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg"></i> Tolak Data
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('approval.reject', $tempData->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel"><i class="bi bi-exclamation-circle"></i> Tolak Data</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alasan" class="form-label fw-bold">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Contoh: Data jumlah produksi tidak sesuai dengan log mesin..." required></textarea>
                            <div class="form-text text-danger">* Wajib diisi agar staff bisa melakukan perbaikan.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection