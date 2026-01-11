<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Produksi #{{ $data->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #f4f6f9;
            font-size: 16px;
        }
        
        .detail-header {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
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

        /* RESPONSIVE STYLES FOR MOBILE */
        @media (max-width: 768px) {
            body {
                font-size: 14px;
                padding-top: 0;
            }

            .detail-header {
                padding: 12px 0;
                margin-bottom: 15px;
            }

            .detail-header h3 {
                font-size: 1.25rem !important;
                margin-bottom: 8px !important;
            }

            .detail-header > .container > .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0 !important;
            }

            .detail-header .gap-3 {
                gap: 0 !important;
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .detail-header .no-print {
                width: 100%;
                margin-top: 12px;
                display: flex !important;
                gap: 8px;
            }

            .detail-header .no-print .btn {
                flex: 1;
                font-size: 0.875rem;
                padding: 10px 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            .status-badge {
                font-size: 0.75rem;
                padding: 4px 10px;
                margin-top: 6px;
                margin-bottom: 6px;
            }

            .detail-header .text-muted {
                font-size: 0.8rem;
                margin-top: 4px;
            }

            .data-card {
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .data-card.p-4 {
                padding: 16px !important;
            }

            .data-card h5 {
                font-size: 1rem !important;
                margin-bottom: 16px !important;
                padding-bottom: 12px !important;
            }

            .data-card h5 i {
                font-size: 0.9rem;
            }

            .row.g-4 {
                row-gap: 16px !important;
            }

            .data-card .row.g-4 > div {
                margin-bottom: 12px;
            }

            .card-label {
                font-size: 0.7rem;
                margin-bottom: 6px;
                font-weight: 600;
            }

            .card-value {
                font-size: 1rem;
                line-height: 1.4;
                word-break: break-word;
            }

            .kpi-card {
                padding: 16px;
                margin-bottom: 15px;
                border-radius: 10px;
            }

            .kpi-value {
                font-size: 1.75rem;
                margin-bottom: 8px;
            }

            .kpi-title {
                font-size: 0.85rem;
                margin-bottom: 8px;
            }

            .kpi-card small {
                font-size: 0.8rem;
            }

            /* Table Responsive */
            .table {
                font-size: 0.85rem;
                margin-bottom: 0;
            }

            .table th,
            .table td {
                padding: 10px 8px;
                vertical-align: middle;
            }

            .table thead th {
                font-size: 0.8rem;
                font-weight: 600;
            }

            .mt-4 {
                margin-top: 1.25rem !important;
            }

            h6.fw-bold {
                font-size: 0.95rem;
                margin-bottom: 12px !important;
            }

            /* Container padding */
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .pb-5 {
                padding-bottom: 2rem !important;
            }
        }

        /* EXTRA SMALL DEVICES */
        @media (max-width: 480px) {
            .detail-header h3 {
                font-size: 1.1rem !important;
            }

            .card-value {
                font-size: 0.95rem;
            }

            .kpi-value {
                font-size: 1.5rem;
            }

            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 8px 6px;
            }

            .data-card.p-4 {
                padding: 14px !important;
            }

            .kpi-card {
                padding: 14px;
            }
        }

        /* PRINT STYLES */
        @media print {
            .no-print, 
            .btn, 
            .sidebar, 
            .top-navbar,
            .main-header,
            header, 
            footer { 
                display: none !important; 
            }

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

            .data-card, .kpi-card, .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                height: auto !important; 
                min-height: 0 !important;
                max-height: none !important;
                overflow: visible !important;
                display: block !important;
                page-break-inside: avoid !important;
                margin-bottom: 20px !important;
            }

            .table-responsive {
                overflow: visible !important;
                display: block !important;
                width: 100% !important;
            }
            
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            .detail-header {
                border-bottom: 2px solid #000 !important;
                padding-bottom: 10px !important;
                margin-bottom: 20px !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
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
                    <div class="d-flex align-items-center gap-3 mb-1 flex-wrap">
                        <h3 class="fw-bold mb-0 text-dark">Laporan Produksi #{{ $data->id }}</h3>
                        <span class="status-badge">
                            <i class="bi bi-check-circle-fill"></i> Verified Data
                        </span>
                    </div>
                    <p class="text-muted mb-0 small">Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="d-flex gap-2 no-print">
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="bi bi-printer"></i> <span class="d-none d-sm-inline">Print</span>
                    </button>
                    <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-primary">
                         <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
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

                    <div class="mt-4">
                        <h6 class="fw-bold mb-3 text-secondary">Rincian Output</h6>
                        <div class="table-responsive">
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

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>