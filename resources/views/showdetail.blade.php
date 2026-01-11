@extends('layouts.app_spv')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Laporan Produksi #{{ $data->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; }
        
        .detail-header {
            background: white;
            padding: 25px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }

        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border: 1px solid #f3f4f6;
            height: 100%;
        }

        .card-label {
            font-size: 0.8rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .card-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
        }

        .kpi-card {
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid transparent;
        }

        .kpi-success { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
        .kpi-warning { background: #fffbeb; border-color: #fde68a; color: #b45309; }
        .kpi-danger { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }

        .kpi-value { font-size: 2rem; font-weight: 700; margin-bottom: 5px; }
        .kpi-title { font-size: 0.9rem; font-weight: 500; opacity: 0.9; }

       @media print {
        /* 1. Sembunyikan elemen Navigasi & Sidebar Dashboard */
        .no-print, 
        .btn, 
        .sidebar, 
        .top-navbar,
        .main-header,
        header, 
        footer { 
            display: none !important; 
        }

        /* 2. Reset Layout Utama */
        body, html, .content-area, .main-content {
            background-color: white !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            overflow: visible !important;
        }

        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* 3. MEMBUAT LAYOUT MENJADI 1 KOLOM (STACKING) */
        /* Ini kunci agar kanan tidak terpotong */
        .row {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
        }

        .col-lg-8, .col-lg-4, .col-md-6 {
            width: 100% !important;
            flex: none !important;
            max-width: 100% !important;
            display: block !important;
            padding: 0 !important;
            margin-bottom: 20px !important;
        }

        /* 4. PERBAIKAN CARD TERPOTONG & SCROLLBAR */
        .data-card, .kpi-card, .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            
            /* Paksa tinggi otomatis mengikuti isi konten */
            height: auto !important; 
            min-height: 0 !important;
            max-height: none !important;
            
            /* Matikan scrollbar */
            overflow: visible !important;
            display: block !important;
            
            /* Mencegah card terbelah di tengah halaman */
            page-break-inside: avoid !important;
            margin-bottom: 20px !important;
        }

        /* 5. Tabel Full Width */
        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        /* 6. Header Detail */
        .detail-header {
            border-bottom: 2px solid #000 !important;
            padding-bottom: 10px !important;
            margin-bottom: 20px !important;
        }

        /* 7. Cetak Warna Background */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* 8. Hapus Scrollbar Bawaan Browser */
        ::-webkit-scrollbar {
            display: none;
        }
    }
    
    </style>
</head>
<body>

    <div class="detail-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <h3 class="fw-bold mb-0 text-dark">Laporan Produksi #{{ $data->id }}</h3>
                        <span class="status-badge">
                            <i class="bi bi-check-circle-fill"></i> Verified Data
                        </span>
                    </div>
                    <p class="text-muted mb-0">Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="d-flex gap-2 no-print">
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="{{ route('dashboard_spv') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            
            <div class="col-lg-8">
                <div class="data-card p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-info-circle me-2"></i> Detail Operasional</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card-label">Tanggal Produksi</div>
                            <div class="card-value">{{ \Carbon\Carbon::parse($data->Tanggal_Produksi)->translatedFormat('l, d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-label">Staff QC / Operator</div>
                            <div class="card-value">{{ $data->User }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-label">Line Produksi</div>
                            <div class="card-value fs-5 text-primary">{{ $data->Line_Produksi }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-label">Shift Kerja</div>
                            <div class="card-value">Shift {{ $data->Shift_Produksi }}</div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6 class="fw-bold mb-3 text-secondary">Rincian Output</h6>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah (Unit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Target Plan</td>
                                    <td class="text-end fw-bold">{{ number_format($data->Target_Produksi) }}</td>
                                </tr>
                                <tr>
                                    <td>Output Aktual (Gross)</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($data->Jumlah_Produksi) }}</td>
                                </tr>
                                <tr>
                                    <td>Produk Cacat (Defect)</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format($data->Jumlah_Produksi_Cacat) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Output Bersih (Good Qty)</strong></td>
                                    <td class="text-end fw-bold">{{ number_format($data->Jumlah_Produksi - $data->Jumlah_Produksi_Cacat) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3 h-100">
                    
                    <div class="kpi-card {{ $achievement >= 100 ? 'kpi-success' : 'kpi-warning' }}">
                        <div class="kpi-title">Pencapaian Target (Achievement)</div>
                        <div class="kpi-value">{{ number_format($achievement, 1) }}%</div>
                        <div class="progress mt-2" style="height: 6px; background: rgba(0,0,0,0.1);">
                            <div class="progress-bar {{ $achievement >= 100 ? 'bg-success' : 'bg-warning' }}" 
                                 style="width: {{ min($achievement, 100) }}%"></div>
                        </div>
                        <small class="mt-2 d-block">Target: {{ number_format($data->Target_Produksi) }} Unit</small>
                    </div>
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@endsection