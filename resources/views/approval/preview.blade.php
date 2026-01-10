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

        .btn-action {
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-approve {
            background-color: #059669;
            color: white;
            border: none;
        }
        .btn-approve:hover { background-color: #047857; transform: translateY(-2px); }

        .btn-reject {
            background-color: #dc2626;
            color: white;
            border: none;
        }
        .btn-reject:hover { background-color: #b91c1c; transform: translateY(-2px); }

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
                                <span class="fw-bold">Pencapaian Target</span>
                                <span class="{{ $achievement >= 100 ? 'text-success' : 'text-warning' }} fw-bold">{{ number_format($achievement, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $achievement >= 100 ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ min($achievement, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">