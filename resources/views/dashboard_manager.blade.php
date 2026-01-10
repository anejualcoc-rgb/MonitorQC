@extends('layouts.app_spv')

@section('title', 'Dashboard Monitoring Produksi')

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
        <h2>Dashboard Monitoring Produksi</h2>
        <div class="date">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    @php
        $totalProduksi = $data->sum('Jumlah_Produksi');
        $totalTarget = $data->sum('Target_Produksi');
        $totalCacat = $data->sum('Jumlah_Produksi_Cacat');
        $persentaseCacat = $totalProduksi > 0 ? ($totalCacat / $totalProduksi) * 100 : 0;

        // trend data: group by tanggal (format Y-m-d)
        $trendGrouped = $data->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->Tanggal_Produksi)->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('Jumlah_Produksi');
        });

        $trendLabels = $trendGrouped->keys()->values();
        $trendData = $trendGrouped->values()->map(function($v){ return (int) $v; });

        // distribusi by line
        $lineGrouped = $data->groupBy('Line_Produksi')->map(function($g){ return $g->sum('Jumlah_Produksi'); });

        // shift summary
        $shiftGrouped = $data->groupBy('Shift_Produksi')->map(function($g){ return $g->sum('Jumlah_Produksi'); });

        // severity distribution from data_defect
        $severityGrouped = $data_defect->groupBy('Severity')->map(function($g){ return $g->sum('Jumlah_Cacat_perjenis'); });

        // top defect types
        $defectTypes = $data_defect->groupBy('Jenis_Defect')->map(function($g){ return $g->sum('Jumlah_Cacat_perjenis'); });
        $topDefects = $defectTypes->sortDesc()->take(10);

        // distribution labels/data for charts
        $distribusiLabels = $lineGrouped->keys();
        $distribusiData = $lineGrouped->values();

        $shiftLabels = $shiftGrouped->keys();
        $shiftData = $shiftGrouped->values();
    @endphp

    <style>
        /* Download Box */
        .download-box {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            display: block;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .download-box {
                margin: 0 0.5rem 1rem 0.5rem;
            }
        }

        .download-btn {
            background-color: #015255ff;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .download-btn:hover {
            background-color: #013d3f;
            text-decoration: none;
            color: white;
        }

        /* Stats Grid Responsive */
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

        /* Chart Grid Responsive */
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

        /* Chart Card */
        .chart-card {
            overflow: visible;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Chart Canvas Height */
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
            
            .chart-card {
                margin-bottom: 1rem;
            }
        }

        /* Table Responsive */
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
            min-width: 800px;
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
            
            .badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
        }

        /* Page Header Responsive */
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
            
            .page-header .date {
                font-size: 0.875rem;
            }
        }

        /* Chart Header Responsive */
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

        /* Top Production List */
        .top-production-list {
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .top-production-list {
                padding: 0.75rem;
            }
            
            .production-item {
                padding: 0.5rem 0 !important;
                font-size: 0.875rem;
            }
        }

        /* Alert Responsive */
        @media (max-width: 768px) {
            .alert-modern {
                padding: 0.75rem;
                font-size: 0.875rem;
            }
        }

        /* Utility Classes */
        @media (max-width: 576px) {
            .mb-4 {
                margin-bottom: 1rem !important;
            }
        }
    </style>

    <div class="download-box">
        <a href="{{ route('export') }}" class="download-btn">
            <i class="bi bi-download"></i> Download Data Excel
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Produksi</div>
                    <div class="stat-value">{{ number_format($totalProduksi) }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>vs last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Target</div>
                    <div class="stat-value">{{ number_format($totalTarget) }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>vs last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-bullseye"></i>
                </div>
            </div>
        </div>

        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Cacat</div>
                    <div class="stat-value">{{ number_format($totalCacat) }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-arrow-down"></i>
                        <span>vs last month</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Persentase Cacat</div>
                    <div class="stat-value">{{ number_format($persentaseCacat, 1) }}%</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up"></i>
                        <span>improvement</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-percent"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-graph-up"></i>
                    Trend Produksi Harian
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="produksiChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Distribusi Produksi (By Line)
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="distribusiChart"></canvas>
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
                    <i class="bi bi-diagram-3"></i>
                    Top Produksi by Line
                </div>
            </div>
            <div class="top-production-list">
                @foreach($distribusiData as $i => $val)
                <div class="production-item" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-gear-fill" style="color: #6366f1; font-size: 1.25rem;"></i>
                        <span style="font-weight: 500;">{{ $distribusiLabels[$i] }}</span>
                    </div>
                    <span style="font-weight: 600; color: #1f2937;">{{ number_format($val) }}</span>
                </div>
                @endforeach
            </div>
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
            <table class="modern-table">
                <thead>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->User }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->Tanggal_Produksi)->format('d/m/Y') }}</td>
                        <td><span class="badge badge-blue">{{ $row->Shift_Produksi }}</span></td>
                        <td><span class="badge badge-gray">{{ $row->Line_Produksi }}</span></td>
                        <td>{{ number_format($row->Jumlah_Produksi) }}</td>
                        <td>{{ number_format($row->Target_Produksi) }}</td>
                        <td><span class="badge badge-red">{{ number_format($row->Jumlah_Produksi_Cacat) }}</span></td>
                        <td>
                            @php
                                $achievement = $row->Target_Produksi > 0 ? ($row->Jumlah_Produksi / $row->Target_Produksi) * 100 : 0;
                                $badgeClass = $achievement >= 100 ? 'badge-green' : ($achievement >= 80 ? 'badge-orange' : 'badge-red');
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ number_format($achievement, 2) }}%</span>
                        </td>
                        <td>
                            @php
                                $defectRate = $row->Jumlah_Produksi > 0 ? ($row->Jumlah_Produksi_Cacat / $row->Jumlah_Produksi) * 100 : 0;
                            @endphp
                            {{ number_format($defectRate, 2) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-card">
        <div class="chart-header" style="margin-bottom: 1rem;">
            <div class="chart-title">
                <i class="bi bi-bug"></i>
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
                        <th>Jumlah Cacat</th>
                        <th>Severity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_defect as $index => $defect)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($defect->Tanggal_Produksi)->format('d/m/Y') }}</td>
                        <td>{{ $defect->Nama_Barang }}</td>
                        <td>{{ $defect->Jenis_Defect }}</td>
                        <td><span class="badge badge-orange">{{ number_format($defect->Jumlah_Cacat_perjenis) }}</span></td>
                        <td>
                            @php
                                $severityBadge = $defect->Severity === 'Critical' ? 'badge-red' : ($defect->Severity === 'Major' ? 'badge-orange' : 'badge-blue');
                            @endphp
                            <span class="badge {{ $severityBadge }}">{{ $defect->Severity }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Data from server
    const produksiData = @json($data);
    const defectData = @json($data_defect);

    // Trend labels & data (unique dates)
    const produksiByDate = {};
    const defectByDate = {};

    produksiData.forEach(d => {
        const date = d.Tanggal_Produksi;
        produksiByDate[date] = (produksiByDate[date] || 0) + Number(d.Jumlah_Produksi);
    });

    defectData.forEach(d => {
        const date = d.Tanggal_Produksi;
        defectByDate[date] = (defectByDate[date] || 0) + Number(d.Jumlah_Cacat_perjenis || d.Jumlah_Produksi_Cacat || 0);
    });

    const trendLabels = Object.keys(produksiByDate);
    const trendProduksi = Object.values(produksiByDate);
    const trendDefect = trendLabels.map(l => defectByDate[l] || 0);

    // Chart.js responsive config
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
                position: window.innerWidth > 768 ? 'top' : 'bottom',
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
                ticks: {
                    font: {
                        size: window.innerWidth > 768 ? 12 : 9
                    }
                }
            }
        }
    };

    // Produksi Chart
    new Chart(document.getElementById('produksiChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Produksi',
                data: trendProduksi,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.08)',
                fill: true,
                tension: 0.3
            }, {
                label: 'Cacat',
                data: trendDefect,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                fill: true,
                tension: 0.3
            }]
        },
        options: responsiveOptions
    });

    // Distribusi by Line (doughnut)
    new Chart(document.getElementById('distribusiChart'), {
        type: 'doughnut',
        data: {
            labels: @json($distribusiLabels),
            datasets: [{
                data: @json($distribusiData),
                backgroundColor: ['#6366f1','#f59e0b','#10b981','#ef4444','#6f42c1','#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
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

    // Shift chart
    new Chart(document.getElementById('shiftChart'), {
        type: 'pie',
        data: {
            labels: @json($shiftLabels),
            datasets: [{
                data: @json($shiftData),
                backgroundColor: ['#0dcaf0','#ffc107','#6f42c1']
            }]
        },
        options: {
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
</script>
@endpush