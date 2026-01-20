@extends('layouts.app_spv')

@section('title', 'Data Produksi')

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
        <h2>Data Produksi</h2>
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
    </style>

    <!-- Filter Box -->
    <div class="filter-box">
        <div class="filter-header">
            <i class="bi bi-funnel"></i>
            Filter Data Produksi
        </div>
        <form action="{{ route('produksi.index_spv') }}" method="GET" class="filter-form">
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
                    <a href="{{ route('produksi.index_spv') }}" class="btn-filter btn-secondary">
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
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Produksi</div>
                    <div class="stat-value">{{ number_format($totalProduksi) }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-boxes"></i>
                        <span>Unit diproduksi</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Target</div>
                    <div class="stat-value">{{ number_format($totalTarget) }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-bullseye"></i>
                        <span>Target keseluruhan</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-flag"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Achievement</div>
                    <div class="stat-value">{{ number_format($achievement, 1) }}%</div>
                    <div class="stat-change {{ $achievement >= 100 ? 'positive' : 'negative' }}">
                        <i class="bi bi-{{ $achievement >= 100 ? 'arrow-up' : 'arrow-down' }}"></i>
                        <span>{{ $achievement >= 100 ? 'Target tercapai' : 'Belum tercapai' }}</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Cacat</div>
                    <div class="stat-value">{{ number_format($totalCacat) }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-percent"></i>
                        <span>{{ number_format($persentaseCacat, 2) }}% dari produksi</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-graph-up"></i>
                    Trend Produksi vs Target
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Distribusi Produksi per Line
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-clock"></i>
                    Produksi per Shift
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="shiftChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-bar-chart-line"></i>
                    Achievement per Line
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="achievementChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="table-card">
        <div class="chart-header" style="margin-bottom: 1rem;">
            <div class="chart-title">
                <i class="bi bi-table"></i>
                Data Produksi Detail
            </div>
        </div>
        <div class="table-card">
    <div class="chart-header" style="margin-bottom: 1rem;">
        <div class="chart-title">
            <i class="bi bi-table"></i>
            Data Produksi Detail
        </div>
    </div>
    <div class="table-responsive">
        <table class="modern-table table-hover"> <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Line</th>
                    <th>Produksi</th>
                    <th>Target</th>
                    <th>Cacat</th>
                    <th>Achievement</th>
                    <th>Defect Rate</th>
                    <th>Status</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                
                <tr style="cursor: pointer;" onclick="window.location='{{ route('produksi.show', $row->id) }}'">
                    
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->User }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->Tanggal_Produksi)->format('d/m/Y') }}</td>
                    <td><span class="badge badge-blue">{{ $row->Shift_Produksi }}</span></td>
                    <td><span class="badge badge-gray">{{ $row->Line_Produksi }}</span></td>
                    <td><strong>{{ number_format($row->Jumlah_Produksi) }}</strong></td>
                    <td>{{ number_format($row->Target_Produksi) }}</td>
                    <td><span class="badge badge-red">{{ number_format($row->Jumlah_Produksi_Cacat) }}</span></td>
                    <td>
                        @php
                            $ach = $row->Target_Produksi > 0 ? ($row->Jumlah_Produksi / $row->Target_Produksi) * 100 : 0;
                            $badgeClass = $ach >= 100 ? 'badge-green' : ($ach >= 80 ? 'badge-orange' : 'badge-red');
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ number_format($ach, 1) }}%</span>
                    </td>
                    <td>
                        @php
                            $defectRate = $row->Jumlah_Produksi > 0 ? ($row->Jumlah_Produksi_Cacat / $row->Jumlah_Produksi) * 100 : 0;
                        @endphp
                        {{ number_format($defectRate, 2) }}%
                    </td>
                    <td>
                        @if($ach >= 100)
                            <span class="badge badge-green">
                                <i class="bi bi-check-circle"></i> Target Tercapai
                            </span>
                        @else
                            <span class="badge badge-orange">
                                <i class="bi bi-dash-circle"></i> Belum Tercapai
                            </span>
                        @endif
                    </td>
                    {{-- Icon Panah kecil --}}
                    <td>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" style="text-align: center; padding: 2rem; color: #6b7280;">
                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                        Tidak ada data produksi
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
    const trendData = @json($trendGrouped);
    const lineData = @json($lineGrouped);
    const shiftData = @json($shiftGrouped);

    // Trend Chart Data
    const trendLabels = Object.keys(trendData);
    const trendProduksi = trendLabels.map(date => trendData[date].produksi);
    const trendTarget = trendLabels.map(date => trendData[date].target);
    const trendCacat = trendLabels.map(date => trendData[date].cacat);

    // Line Chart Data
    const lineLabels = Object.keys(lineData);
    const lineProduksi = lineLabels.map(line => lineData[line].produksi);

    // Shift Chart Data
    const shiftLabels = Object.keys(shiftData);
    const shiftProduksi = shiftLabels.map(shift => shiftData[shift].produksi);

    // Achievement per Line
    const lineAchievement = lineLabels.map(line => {
        const target = lineData[line].target;
        const produksi = lineData[line].produksi;
        return target > 0 ? (produksi / target * 100).toFixed(1) : 0;
    });

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
            }
        },
        scales: {
            x: {
                ticks: {
                    font: {
                        size: window.innerWidth > 768 ? 12 : 9
                    },
                    maxRotation: window.innerWidth > 768 ? 45 : 90,
                    minRotation: window.innerWidth > 768 ? 0 : 45,
                    autoSkip: true,
                    maxTicksLimit: window.innerWidth > 768 ? 10 : 5
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: window.innerWidth > 768 ? 12 : 9
                    }
                }
            }
        }
    };

    // Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Produksi',
                data: trendProduksi,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Target',
                data: trendTarget,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true,
                tension: 0.4,
                borderDash: [5, 5]
            }]
        },
        options: responsiveOptions
    });

    // Line Distribution Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'doughnut',
        data: {
            labels: lineLabels,
            datasets: [{
                data: lineProduksi,
                backgroundColor: ['#6366f1','#f59e0b','#10b981','#ef4444','#6f42c1','#0dcaf0','#20c997','#fd7e14']
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
                }
            }
        }
    });

    // Shift Chart
    new Chart(document.getElementById('shiftChart'), {
        type: 'pie',
        data: {
            labels: shiftLabels,
            datasets: [{
                data: shiftProduksi,
                backgroundColor: ['#0dcaf0','#ffc107','#6f42c1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                }
            }
        }
    });

    new Chart(document.getElementById('achievementChart'), {
        type: 'bar',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Achievement (%)',
                data: lineAchievement,
                backgroundColor: lineAchievement.map(val => val >= 100 ? '#10b981' : val >= 80 ? '#f59e0b' : '#ef4444'),
                borderRadius: 6
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
                ...responsiveOptions.scales,
                y: {
                    ...responsiveOptions.scales.y,
                    max: 120,
                    ticks: {
                        ...responsiveOptions.scales.y.ticks,
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush