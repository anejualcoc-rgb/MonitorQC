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
        <h2>Dashboard Overview</h2>
        <div class="date">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    @php
        // Summary calculations
        $totalProduksi = $data->sum('Jumlah_Produksi');
        $totalTarget = $data->sum('Target_Produksi');
        $totalCacat = $data->sum('Jumlah_Produksi_Cacat');
        $achievement = $totalTarget > 0 ? ($totalProduksi / $totalTarget) * 100 : 0;
        $defectRate = $totalProduksi > 0 ? ($totalCacat / $totalProduksi) * 100 : 0;

        // Critical defects
        $criticalDefects = $data_defect->where('Severity', 'Critical')->sum('Jumlah_Cacat_perjenis');
        
        // Line performance
        $lineGrouped = $data->groupBy('Line_Produksi')->map(function($g) {
            return [
                'produksi' => $g->sum('Jumlah_Produksi'),
                'target' => $g->sum('Target_Produksi'),
                'cacat' => $g->sum('Jumlah_Produksi_Cacat')
            ];
        });
        
        // Best performing line
        $bestLine = $lineGrouped->sortByDesc(function($item) {
            return $item['target'] > 0 ? ($item['produksi'] / $item['target']) * 100 : 0;
        })->first();
        $bestLineName = $lineGrouped->search($bestLine);
        $bestLineAchievement = $bestLine['target'] > 0 ? ($bestLine['produksi'] / $bestLine['target']) * 100 : 0;

        // Trend (last 7 days)
        $last7Days = $data->where('Tanggal_Produksi', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->Tanggal_Produksi)->format('Y-m-d');
            })->map(function($g) {
                return $g->sum('Jumlah_Produksi');
            })->sortKeys();

        // Top defect types
        $topDefects = $data_defect->groupBy('Jenis_Defect')
            ->map(function($g) {
                return $g->sum('Jumlah_Cacat_perjenis');
            })->sortDesc()->take(5);
    @endphp

    <style>
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-color: #015255ff;
            color: #015255ff;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .action-icon.blue { background: #eff6ff; color: #1e40af; }
        .action-icon.green { background: #f0fdf4; color: #15803d; }
        .action-icon.orange { background: #fff7ed; color: #c2410c; }
        .action-icon.red { background: #fef2f2; color: #b91c1c; }

        .action-content {
            flex: 1;
        }

        .action-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .action-title {
            font-weight: 600;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: 1fr;
                padding: 0 0.5rem;
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
        }

        /* Cards Grid - OPTIMIZED */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 0 0.5rem;
            }
        }

        .card-col-8 { grid-column: span 8; }
        .card-col-4 { grid-column: span 4; }
        .card-col-6 { grid-column: span 6; }
        .card-col-3 { grid-column: span 3; }
        .card-col-9 { grid-column: span 9; }
        .card-col-12 { grid-column: span 12; }

        @media (max-width: 768px) {
            .card-col-8, .card-col-4, .card-col-6, .card-col-3, .card-col-9 {
                grid-column: span 1;
            }
        }

        /* Info Card */
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            height: 100%;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .card-title {
            font-weight: 600;
            font-size: 1rem;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-action {
            font-size: 0.875rem;
            color: #015255ff;
            text-decoration: none;
            font-weight: 500;
        }

        .card-action:hover {
            color: #013d3f;
        }

        /* Recent Items */
        .recent-item {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            gap: 1rem;
            align-items: start;
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .recent-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .recent-content {
            flex: 1;
            min-width: 0;
        }

        .recent-title {
            font-weight: 600;
            font-size: 0.875rem;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .recent-desc {
            font-size: 0.813rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .recent-time {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .recent-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Performance List */
        .performance-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .performance-item:last-child {
            border-bottom: none;
        }

        .performance-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .performance-rank {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .performance-rank.top { background: #fef3c7; color: #92400e; }

        .performance-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .performance-right {
            text-align: right;
        }

        .performance-value {
            font-weight: 700;
            font-size: 0.875rem;
            color: #1f2937;
        }

        .performance-percent {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 280px;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }
        }

        /* Best Performer Card */
        .best-performer-content {
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .trophy-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }

        .best-line-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .best-achievement {
            font-size: 2rem;
            font-weight: 800;
            color: #f59e0b;
            margin-bottom: 0.5rem;
        }

        .achievement-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .best-stats {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f3f4f6;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .best-stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }

        .best-stat-label {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .best-stat-value.red {
            color: #ef4444;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
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
    </style>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('produksi.index_spv') }}" class="action-btn">
            <div class="action-icon blue">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="action-content">
                <div class="action-label">Lihat Detail</div>
                <div class="action-title">Data Produksi</div>
            </div>
        </a>

        <a href="{{ route('defect.index_spv') }}" class="action-btn">
            <div class="action-icon red">
                <i class="bi bi-bug"></i>
            </div>
            <div class="action-content">
                <div class="action-label">Lihat Detail</div>
                <div class="action-title">Data Defect</div>
            </div>
        </a>

        <a href="{{ route('export') }}" class="action-btn">
            <div class="action-icon green">
                <i class="bi bi-download"></i>
            </div>
            <div class="action-content">
                <div class="action-label">Download</div>
                <div class="action-title">Export Excel</div>
            </div>
        </a>
    </div>

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

        <div class="stat-card {{ $achievement >= 100 ? 'green' : 'orange' }}">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Achievement</div>
                    <div class="stat-value">{{ number_format($achievement, 1) }}%</div>
                    <div class="stat-change {{ $achievement >= 100 ? 'positive' : 'negative' }}">
                        <i class="bi bi-{{ $achievement >= 100 ? 'check-circle' : 'dash-circle' }}"></i>
                        <span>{{ $achievement >= 100 ? 'Target tercapai' : 'Dari target' }}</span>
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
                    <div class="stat-label">Defect Rate</div>
                    <div class="stat-value">{{ number_format($defectRate, 2) }}%</div>
                    <div class="stat-change negative">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>{{ number_format($totalCacat) }} unit cacat</span>
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
                    <div class="stat-label">Critical Defects</div>
                    <div class="stat-value">{{ number_format($criticalDefects) }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-shield-exclamation"></i>
                        <span>Perlu perhatian</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid - REORGANIZED -->
    <div class="cards-grid">
        <!-- Row 1: Production Trend (9 cols) + Best Performer (3 cols) -->
        <div class="card-col-9">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-graph-up"></i>
                        Trend Produksi (7 Hari Terakhir)
                    </div>
                    <a href="{{ route('produksi.index_spv') }}" class="card-action">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card-col-3">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-trophy-fill"></i>
                        Line Terbaik
                    </div>
                </div>
                <div class="best-performer-content">
                    <div class="trophy-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="best-line-name">{{ $bestLineName }}</div>
                    <div class="best-achievement">{{ number_format($bestLineAchievement, 1) }}%</div>
                    <div class="achievement-label">Achievement Rate</div>
                    <div class="best-stats">
                        <div>
                            <div class="best-stat-value">{{ number_format($bestLine['produksi']) }}</div>
                            <div class="best-stat-label">Produksi</div>
                        </div>
                        <div>
                            <div class="best-stat-value">{{ number_format($bestLine['target']) }}</div>
                            <div class="best-stat-label">Target</div>
                        </div>
                        <div>
                            <div class="best-stat-value red">{{ number_format($bestLine['cacat']) }}</div>
                            <div class="best-stat-label">Cacat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-col-4">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-award"></i>
                        Performa Line Produksi
                    </div>
                </div>
                <div>
                    @php
                        $linePerformance = $lineGrouped->map(function($item, $line) {
                            return [
                                'line' => $line,
                                'produksi' => $item['produksi'],
                                'achievement' => $item['target'] > 0 ? ($item['produksi'] / $item['target']) * 100 : 0
                            ];
                        })->sortByDesc('achievement')->values();
                    @endphp

                    @forelse($linePerformance as $index => $perf)
                        <div class="performance-item">
                            <div class="performance-left">
                                <div class="performance-rank {{ $index < 3 ? 'top' : '' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="performance-name">{{ $perf['line'] }}</div>
                                </div>
                            </div>
                            <div class="performance-right">
                                <div class="performance-value">{{ number_format($perf['produksi']) }}</div>
                                <div class="performance-percent">{{ number_format($perf['achievement'], 1) }}%</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <div>Tidak ada data</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card-col-4">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-bug-fill"></i>
                        Top 5 Jenis Defect
                    </div>
                    <a href="{{ route('defect.index_spv') }}" class="card-action">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="chart-container">
                    <canvas id="defectChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card-col-4">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-bell"></i>
                        Notifikasi Terbaru
                    </div>
                </div>
                <div>
                    @php
                        $notifications = [
                            [
                                'type' => 'critical',
                                'title' => 'Critical Defect Detected',
                                'desc' => 'Line A mencapai 5 critical defects',
                                'time' => '2 jam lalu',
                                'icon' => 'exclamation-triangle-fill',
                                'bg' => '#fef2f2',
                                'color' => '#b91c1c'
                            ],
                            [
                                'type' => 'achievement',
                                'title' => 'Target Tercapai',
                                'desc' => 'Line B mencapai 105% target hari ini',
                                'time' => '4 jam lalu',
                                'icon' => 'check-circle-fill',
                                'bg' => '#f0fdf4',
                                'color' => '#15803d'
                            ],
                            [
                                'type' => 'warning',
                                'title' => 'Produksi Menurun',
                                'desc' => 'Line C produksi turun 15% dari kemarin',
                                'time' => '6 jam lalu',
                                'icon' => 'info-circle-fill',
                                'bg' => '#fff7ed',
                                'color' => '#c2410c'
                            ],
                        ];
                    @endphp

                    @forelse($notifications as $notif)
                        <div class="recent-item">
                            <div class="recent-icon" style="background: {{ $notif['bg'] }}; color: {{ $notif['color'] }}">
                                <i class="bi bi-{{ $notif['icon'] }}"></i>
                            </div>
                            <div class="recent-content">
                                <div class="recent-title">{{ $notif['title'] }}</div>
                                <div class="recent-desc">{{ $notif['desc'] }}</div>
                                <div class="recent-time">
                                    <i class="bi bi-clock"></i> {{ $notif['time'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-bell-slash"></i>
                            <div>Tidak ada notifikasi</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Row 3: Pending Approvals (Full Width) -->
        <div class="card-col-12">
            <div class="info-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="bi bi-clipboard-check"></i>
                        Menunggu Approval
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    @php
                        $approvals = [
                            [
                                'title' => 'Laporan Produksi Shift 1',
                                'user' => 'Operator A',
                                'date' => '10 Jan 2026',
                                'status' => 'pending',
                                'badge_class' => 'badge-orange',
                                'badge_text' => 'Pending'
                            ],
                            [
                                'title' => 'Adjustment Target Line B',
                                'user' => 'Supervisor B',
                                'date' => '09 Jan 2026',
                                'status' => 'pending',
                                'badge_class' => 'badge-orange',
                                'badge_text' => 'Pending'
                            ],
                            [
                                'title' => 'Laporan Defect Shift 3',
                                'user' => 'QC Team',
                                'date' => '09 Jan 2026',
                                'status' => 'review',
                                'badge_class' => 'badge-blue',
                                'badge_text' => 'In Review'
                            ],
                        ];
                    @endphp

                    @forelse($approvals as $approval)
                        <div class="recent-item" style="border: 1px solid #f3f4f6; border-radius: 8px; margin: 0;">
                            <div class="recent-icon" style="background: #eff6ff; color: #1e40af;">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <div class="recent-content">
                                <div class="recent-title">{{ $approval['title'] }}</div>
                                <div class="recent-desc">Dari: {{ $approval['user'] }}</div>
                                <div class="recent-time">
                                    <i class="bi bi-calendar"></i> {{ $approval['date'] }}
                                </div>
                            </div>
                            <span class="recent-badge {{ $approval['badge_class'] }}">
                                {{ $approval['badge_text'] }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-check-all"></i>
                            <div>Semua sudah disetujui</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Trend Chart Data
    const trendLabels = @json($last7Days->keys()->values());
    const trendData = @json($last7Days->values()->map(function($v){ return (int) $v; }));

    // Top Defects Data
    const defectLabels = @json($topDefects->keys()->values());
    const defectData = @json($topDefects->values()->map(function($v){ return (int) $v; }));

    // Responsive options
    const responsiveOptions = {
        responsive: true,
        maintainAspectRatio: false,
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
                enabled: true
            }
        }
    };

    // Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Produksi Harian',
                data: trendData,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            }]
        },
        options: {
            ...responsiveOptions,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: window.innerWidth > 768 ? 11 : 9 },
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { size: window.innerWidth > 768 ? 11 : 9 }
                    }
                }
            }
        }
    });

    // Defect Chart
    new Chart(document.getElementById('defectChart'), {
        type: 'doughnut',
        data: {
            labels: defectLabels,
            datasets: [{
                data: defectData,
                backgroundColor: ['#ef4444','#f59e0b','#eab308','#84cc16','#22c55e'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: window.innerWidth > 768 ? 'right' : 'bottom',
                    labels: {
                        boxWidth: window.innerWidth > 768 ? 15 : 12,
                        padding: window.innerWidth > 768 ? 10 : 8,
                        font: {
                            size: window.innerWidth > 768 ? 11 : 9
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
</script>
@endpush