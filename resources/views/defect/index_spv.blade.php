@extends('layouts.app_spv')

@section('title', 'Data Defect')

@section('content')
    @if(session('success'))
        <div class="alert-modern success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="page-header">
        <h2>Data Defect</h2>
        <div class="date">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <style>
        /* Filter Box */
        .filter-box {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
        }

        .form-select {
            padding: 0.625rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background-color: white;
            transition: all 0.2s;
        }

        .form-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
        }

        .btn-filter {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #015255ff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #013d3f;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        @media (max-width: 768px) {
            .filter-box {
                margin: 0 0.5rem 1.5rem 0.5rem;
                padding: 1rem;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-filter {
                width: 100%;
                justify-content: center;
            }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                padding: 0 0.5rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-value {
                font-size: 1.5rem !important;
            }
            
            .stat-icon i {
                font-size: 1.5rem !important;
            }
        }

        /* Chart Grid */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 0 0.5rem;
            }
        }

        .chart-card {
            overflow: visible;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .chart-canvas {
            position: relative;
            height: 300px;
            padding: 1rem;
            overflow: visible;
        }

        @media (max-width: 768px) {
            .chart-canvas {
                height: 280px;
                padding: 1rem 0.5rem;
            }
        }

        /* Table */
        .table-card {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .table-card {
                margin: 0 0.5rem 1.5rem 0.5rem;
            }
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .modern-table {
            width: 100%;
            min-width: 1000px;
        }

        @media (max-width: 768px) {
            .modern-table {
                font-size: 0.875rem;
            }
            
            .modern-table th,
            .modern-table td {
                padding: 0.5rem;
                white-space: nowrap;
            }
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 0 0.5rem;
            }
            
            .page-header h2 {
                font-size: 1.5rem;
            }
        }

        .chart-header {
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .chart-header {
                padding: 0.75rem;
            }
            
            .chart-title {
                font-size: 0.95rem;
            }
        }

        /* Filter Badge */
        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #eff6ff;
            color: #1e40af;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .filter-badge {
                margin: 0 0.5rem 1rem 0.5rem;
            }
        }

        /* Severity Badges */
        .severity-critical {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .severity-major {
            background-color: #fed7aa;
            color: #9a3412;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .severity-minor {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>

    <!-- Filter Box -->
    <div class="filter-box">
        <div class="filter-header">
            <i class="bi bi-funnel"></i>
            Filter Data Defect
        </div>
        <form action="{{ route('defect.index_spv') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">-- Semua Bulan --</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="btn-group">
                    <button type="submit" class="btn-filter btn-primary">
                        <i class="bi bi-search"></i>
                        Tampilkan
                    </button>
                    <a href="{{ route('defect.index_spv') }}" class="btn-filter btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Filter Info Badge -->
    @if($bulan || $tahun)
        <div class="filter-badge">
            <i class="bi bi-info-circle"></i>
            <span>
                Menampilkan data 
                @if($bulan)
                    bulan {{ \Carbon\Carbon::createFromDate(null, $bulan, 1)->translatedFormat('F') }}
                @endif
                @if($tahun)
                    tahun {{ $tahun }}
                @endif
            </span>
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Defect</div>
                    <div class="stat-value">{{ number_format($totalDefect) }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Unit cacat</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-x-octagon"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Defect Rate</div>
                    <div class="stat-value">{{ number_format($defectRate, 2) }}%</div>
                    <div class="stat-change negative">
                        <i class="bi bi-percent"></i>
                        <span>Dari total produksi</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-down-arrow"></i>
                </div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Critical Defects</div>
                    <div class="stat-value">{{ number_format($criticalCount) }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>Kritis</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-shield-fill-exclamation"></i>
                </div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Jenis Defect</div>
                    <div class="stat-value">{{ count($jenisDefectGrouped) }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-list-check"></i>
                        <span>Kategori defect</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Distribusi Jenis Defect
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="jenisDefectChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-bar-chart-fill"></i>
                    Defect by Severity
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="severityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-graph-up"></i>
                    Trend Defect
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-box-seam"></i>
                    Top 10 Produk Bermasalah
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="productChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="table-card">
        <div class="chart-header" style="margin-bottom: 1rem;">
            <div class="chart-title">
                <i class="bi bi-table"></i>
                Data Defect Detail
            </div>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jenis Defect</th>
                        <th>Jumlah</th>
                        <th>Severity</th>
                        <th>Line Produksi</th>
                        <th>Shift</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->Tanggal_Produksi)->format('d/m/Y') }}</td>
                        <td><strong>{{ $row->Nama_Barang }}</strong></td>
                        <td><span class="badge badge-orange">{{ $row->Jenis_Defect }}</span></td>
                        <td><span class="badge badge-red">{{ number_format($row->Jumlah_Cacat_perjenis) }}</span></td>
                        <td>
                            @if($row->Severity == 'Critical')
                                <span class="severity-critical">
                                    <i class="bi bi-exclamation-octagon-fill"></i> Critical
                                </span>
                            @elseif($row->Severity == 'Major')
                                <span class="severity-major">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Major
                                </span>
                            @else
                                <span class="severity-minor">
                                    <i class="bi bi-info-circle-fill"></i> Minor
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($row->produksi)
                                <span class="badge badge-gray">{{ $row->produksi->Line_Produksi }}</span>
                            @else
                                <span class="badge badge-gray">-</span>
                            @endif
                        </td>
                        <td>
                            @if($row->produksi)
                                <span class="badge badge-blue">{{ $row->produksi->Shift_Produksi }}</span>
                            @else
                                <span class="badge badge-blue">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #6b7280;">
                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Tidak ada data defect
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
            // Prepare data from backend
        const jenisDefectData = @json($jenisDefectGrouped);
        const severityData = @json($severityGrouped);
        const productData = @json($productGrouped);
        const trendData = @json($trendGrouped);

        // Debug: Log data untuk troubleshooting
        console.log('=== DEBUG DATA ===');
        console.log('Product Data:', productData);
        console.log('Jenis Defect Data:', jenisDefectData);
        console.log('Severity Data:', severityData);
        console.log('Trend Data:', trendData);

        // PERBAIKAN: Validasi dan transform data dengan aman
        function safeExtractData(data) {
            if (!data || typeof data !== 'object' || Object.keys(data).length === 0) {
                console.warn('Data kosong atau tidak valid:', data);
                return { labels: [], values: [] };
            }
            
            const labels = Object.keys(data);
            const values = labels.map(key => {
                const value = data[key]?.jumlah || data[key];
                return typeof value === 'number' ? value : parseInt(value) || 0;
            });
            
            return { labels, values };
        }

        // Extract data dengan validasi
        const jenisDefect = safeExtractData(jenisDefectData);
        const severity = safeExtractData(severityData);
        const product = safeExtractData(productData);
        const trend = safeExtractData(trendData);

        // Debug: Log hasil mapping
        console.log('=== DEBUG EXTRACTED DATA ===');
        console.log('Product:', product);
        console.log('Jenis Defect:', jenisDefect);
        console.log('Severity:', severity);
        console.log('Trend:', trend);

        // Cek apakah ada data yang kosong
        if (product.labels.length === 0) {
            console.error('PERINGATAN: Data produk kosong!');
        }

        // Responsive options
        const responsiveOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: window.innerWidth > 768 ? 10 : 5,
                    right: window.innerWidth > 768 ? 10 : 5,
                    top: window.innerWidth > 768 ? 10 : 5,
                    bottom: window.innerWidth > 768 ? 10 : 5
                }
            },
            plugins: {
                legend: {
                    display: window.innerWidth > 768,
                    position: 'top',
                    labels: {
                        boxWidth: window.innerWidth > 768 ? 40 : 20,
                        padding: window.innerWidth > 768 ? 10 : 5,
                        font: {
                            size: window.innerWidth > 768 ? 12 : 10
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false
                }
            }
        };

        // Jenis Defect Chart (Doughnut)
        if (jenisDefect.labels.length > 0) {
            new Chart(document.getElementById('jenisDefectChart'), {
                type: 'doughnut',
                data: {
                    labels: jenisDefect.labels,
                    datasets: [{
                        data: jenisDefect.values,
                        backgroundColor: [
                            '#ef4444', '#f59e0b', '#eab308', '#84cc16', '#22c55e',
                            '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1',
                            '#8b5cf6', '#a855f7', '#d946ef', '#ec4899'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: window.innerWidth > 768 ? 'right' : 'bottom',
                            labels: {
                                boxWidth: window.innerWidth > 768 ? 15 : 12,
                                padding: window.innerWidth > 768 ? 10 : 8,
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 10
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            console.log('✓ Jenis Defect Chart created');
        } else {
            console.error('✗ Jenis Defect Chart: No data available');
            document.getElementById('jenisDefectChart').parentElement.innerHTML = 
                '<div style="text-align: center; padding: 2rem; color: #6b7280;">Tidak ada data</div>';
        }

        // Severity Chart (Bar)
        if (severity.labels.length > 0) {
            new Chart(document.getElementById('severityChart'), {
                type: 'bar',
                data: {
                    labels: severity.labels,
                    datasets: [{
                        label: 'Jumlah Defect',
                        data: severity.values,
                        backgroundColor: severity.labels.map(label => {
                            if (label === 'Critical') return '#ef4444';
                            if (label === 'Major') return '#f59e0b';
                            return '#eab308';
                        }),
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    ...responsiveOptions,
                    plugins: {
                        ...responsiveOptions.plugins,
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 9
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 9
                                },
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });
            console.log('✓ Severity Chart created');
        } else {
            console.error('✗ Severity Chart: No data available');
            document.getElementById('severityChart').parentElement.innerHTML = 
                '<div style="text-align: center; padding: 2rem; color: #6b7280;">Tidak ada data</div>';
        }

        // Trend Chart (Line)
        if (trend.labels.length > 0) {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: trend.labels,
                    datasets: [{
                        label: 'Jumlah Defect',
                        data: trend.values,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ef4444',
                        borderWidth: 2
                    }]
                },
                options: {
                    ...responsiveOptions,
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 9
                                },
                                maxRotation: 45,
                                minRotation: 0
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 9
                                },
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });
            console.log('✓ Trend Chart created');
        } else {
            console.error('✗ Trend Chart: No data available');
            document.getElementById('trendChart').parentElement.innerHTML = 
                '<div style="text-align: center; padding: 2rem; color: #6b7280;">Tidak ada data</div>';
        }

        // Product Chart (Horizontal Bar) - DIPERBAIKI
        if (product.labels.length > 0) {
            new Chart(document.getElementById('productChart'), {
                type: 'bar',
                data: {
                    labels: product.labels,
                    datasets: [{
                        label: 'Jumlah Defect',
                        data: product.values,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return `Defect: ${context.parsed.x}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 12 : 9
                                },
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: window.innerWidth > 768 ? 11 : 9
                                },
                                autoSkip: false
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            console.log('✓ Product Chart created with', product.labels.length, 'products');
        } else {
            console.error('✗ Product Chart: No data available');
            document.getElementById('productChart').parentElement.innerHTML = 
                '<div style="text-align: center; padding: 2rem; color: #6b7280;">Tidak ada data</div>';
        }

        // Log final status
        console.log('=== CHART INITIALIZATION COMPLETE ===');
        console.log('Total charts attempted: 4');
        console.log('Check console for any errors above');
</script>
@endpush