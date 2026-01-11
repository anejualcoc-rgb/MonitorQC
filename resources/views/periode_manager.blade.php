@extends('layouts.app_manager')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Produksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --primary-color: #4F46E5;
            --primary-light: rgba(79, 70, 229, 0.1);
            --danger-color: #EF4444;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --purple: #8B5CF6;
            --blue: #3B82F6;
            --green: #10B981;
            --orange: #F97316;
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: #F9FAFB;
            min-height: 100vh;
        }

        .card {
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            transition: all 0.2s;
            background: white;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Header Cards */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.purple::before { background: var(--purple); }
        .stat-card.green::before { background: var(--success-color); }
        .stat-card.red::before { background: var(--danger-color); }
        .stat-card.yellow::before { background: var(--warning-color); }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.purple { background: rgba(139, 92, 246, 0.1); }
        .stat-icon.green { background: rgba(16, 185, 129, 0.1); }
        .stat-icon.red { background: rgba(239, 68, 68, 0.1); }
        .stat-icon.yellow { background: rgba(245, 158, 11, 0.1); }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 13px;
            font-weight: 500;
        }

        .stat-change.up { color: var(--success-color); }
        .stat-change.down { color: var(--danger-color); }

        /* Period Cards */
        .period-card {
            border: 1px solid #E5E7EB;
            background: white;
            border-radius: 12px;
        }

        .period-card.active {
            background: #F9FAFB;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .defect-rate {
            font-size: 24px;
            font-weight: 700;
        }

        .defect-rate.low { color: var(--success-color); }
        .defect-rate.high { color: var(--danger-color); }

        .achievement-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        .achievement-badge.good {
            background: #D1FAE5;
            color: #065F46;
        }

        .achievement-badge.poor {
            background: #FEE2E2;
            color: #991B1B;
        }

        /* Buttons */
        .btn-group .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 14px;
            border: 1px solid #E5E7EB;
            background: white;
            color: #6B7280;
            transition: all 0.2s;
        }

        .btn-group .btn:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }

        .btn-group .btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Table */
        .table-modern {
            border-radius: 12px;
            overflow: hidden;
        }

        .table-modern thead {
            background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }

        .table-modern thead th {
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border: none;
        }

        .table-modern tbody tr {
            border-bottom: 1px solid #F3F4F6;
            transition: background 0.2s;
        }

        .table-modern tbody tr:hover {
            background: #F9FAFB;
        }

        .table-modern tbody td {
            padding: 12px 16px;
            font-size: 14px;
            color: #111827;
            border: none;
        }

        /* Card Headers */
        .card-header-custom {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px 20px;
        }

        .card-header-custom h6 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        /* List Group */
        .list-group-item {
            border: none;
            border-bottom: 1px solid #F3F4F6;
            padding: 16px 20px;
            transition: all 0.2s;
        }

        .list-group-item:hover {
            background: #F9FAFB;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .badge-defect {
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Section Title */
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .page-header p {
            font-size: 14px;
            color: #6B7280;
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .bg-primary-subtle {
            background: rgba(79, 70, 229, 0.1) !important;
            color: var(--primary-color) !important;
        }

        .bg-danger-subtle {
            background: rgba(239, 68, 68, 0.1) !important;
            color: var(--danger-color) !important;
        }

        .period-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center page-header">
        <div class="col-md-8">
            <h2>Dashboard Monitoring Produksi</h2>
            <p class="mb-0">{{ date('d F Y') }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ url()->current() }}?period=daily" 
                   class="btn {{ $periode == 'daily' ? 'active' : '' }}">
                   Harian
                </a>
                <a href="{{ url()->current() }}?period=weekly" 
                   class="btn {{ $periode == 'weekly' ? 'active' : '' }}">
                   Mingguan
                </a>
                <a href="{{ url()->current() }}?period=monthly" 
                   class="btn {{ $periode == 'monthly' ? 'active' : '' }}">
                   Bulanan
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        @php
            $totalProduksi = is_array($dataProduksi) ? array_sum($dataProduksi) : collect($dataProduksi)->sum();
            $totalTarget = is_array($dataTarget) ? array_sum($dataTarget) : collect($dataTarget)->sum();
            $totalCacat = is_array($dataCacat) ? array_sum($dataCacat) : collect($dataCacat)->sum();
            $avgDefectRate = $totalProduksi > 0 ? round(($totalCacat / $totalProduksi) * 100, 1) : 0;
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card purple border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-label">Total Produksi</div>
                        <div class="stat-icon purple">🏭</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalProduksi) }}</div>
                    <div class="stat-change up">↑ vs last month</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card green border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-label">Total Target</div>
                        <div class="stat-icon green">🎯</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalTarget) }}</div>
                    <div class="stat-change up">↑ vs last month</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card red border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-label">Total Cacat</div>
                        <div class="stat-icon red">⚠️</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalCacat) }}</div>
                    <div class="stat-change down">↓ vs last month</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card yellow border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-label">Persentase Cacat</div>
                        <div class="stat-icon yellow">%</div>
                    </div>
                    <div class="stat-number">{{ $avgDefectRate }}%</div>
                    <div class="stat-change up">↑ improvement</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Cards -->
    <div class="mb-4">
        <h4 class="section-title">Data Periode {{ ucfirst($periode) }}</h4>
    </div>

    <div class="row mb-4">
        @foreach($periodCards as $index => $card)
        <div class="col-md-4 mb-3">
            <div class="card period-card {{ $index == 0 ? 'active' : '' }}">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="period-badge bg-{{ $card['badge'] }} text-white">{{ $card['label'] }}</span>
                            <h6 class="text-muted mb-0 mt-2" style="font-size: 13px;">{{ $card['date'] }}</h6>
                        </div>
                        <div class="text-end">
                            <span class="defect-rate {{ $card['data']['defect_rate'] > 2 ? 'high' : 'low' }}">
                                {{ $card['data']['defect_rate'] }}%
                            </span>
                            <div class="stat-label">Defect Rate</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="stat-label">Produksi</div>
                            <div style="font-size: 24px; font-weight: 700; color: #111827;">{{ number_format($card['data']['produksi']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="stat-label">Target</div>
                            <div style="font-size: 24px; font-weight: 700; color: #6B7280;">{{ number_format($card['data']['target']) }}</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="stat-label">Cacat</div>
                            <div style="font-size: 20px; font-weight: 700; color: var(--danger-color);">{{ number_format($card['data']['cacat']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="stat-label">Pencapaian</div>
                            <div style="font-size: 20px; font-weight: 700; {{ $card['data']['achievement'] >= 100 ? 'color: var(--success-color)' : 'color: var(--danger-color)' }}">
                                {{ $card['data']['achievement'] }}%
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <span class="achievement-badge {{ $card['data']['achievement'] >= 100 ? 'good' : 'poor' }} w-100 d-block text-center">
                            {{ $card['data']['achievement'] >= 100 ? '✓ Target Tercapai' : '⚠ Belum Tercapai' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Chart Section -->
    <div class="mb-4">
        <h4 class="section-title">Tren & Visualisasi</h4>
    </div>

    <div class="chart-container mb-4">
        <h5 class="chart-title">Tren Produksi vs Defect Rate ({{ ucfirst($periode) }})</h5>
        <div style="height: 400px;">
            <canvas id="productionChart"></canvas>
        </div>
    </div>

    <!-- Detail Data & Top Defects -->
    <div class="mb-4">
        <h4 class="section-title">Detail & Analisis</h4>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header card-header-custom">
                    <h6 class="mb-0">Detail Data {{ ucfirst($periode) }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th class="text-end">Produksi</th>
                                    <th class="text-end">Target</th>
                                    <th class="text-end">Cacat (Qty)</th>
                                    <th class="text-end">Defect Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chartLabels as $index => $label)
                                <tr>
                                    <td class="fw-semibold">{{ $label }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary-subtle">
                                            {{ number_format($dataProduksi[$index]) }}
                                        </span>
                                    </td>
                                    <td class="text-end text-muted">{{ number_format($dataTarget[$index]) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-danger-subtle">
                                            {{ number_format($dataCacat[$index]) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold {{ $dataDefectRate[$index] > 2 ? 'text-danger' : 'text-success' }}">
                                            {{ $dataDefectRate[$index] }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header card-header-custom">
                    <h6 class="mb-0">Top 5 Jenis Defect</h6>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($topDefects as $defect)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="font-size: 14px;">{{ $defect->Jenis_Defect }}</span>
                            <span class="badge badge-defect bg-danger text-white rounded-pill">{{ number_format($defect->total) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <div class="mb-2" style="font-size: 2rem;">📊</div>
                            Belum ada data defect.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('productionChart').getContext('2d');
    
    const labels = {!! json_encode($chartLabels) !!};
    const dataProduksi = {!! json_encode($dataProduksi) !!};
    const dataTarget = {!! json_encode($dataTarget) !!};
    const dataDefectRate = {!! json_encode($dataDefectRate) !!};

    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Target Produksi',
                    data: dataTarget,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    type: 'line',
                    pointRadius: 4,
                    pointBackgroundColor: '#3B82F6',
                    fill: false,
                    yAxisID: 'y',
                    tension: 0.3
                },
                {
                    label: 'Aktual Produksi',
                    data: dataProduksi,
                    backgroundColor: '#4F46E5',
                    borderColor: '#4338CA',
                    borderWidth: 0,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Defect Rate (%)',
                    data: dataDefectRate,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    type: 'line',
                    yAxisID: 'y1',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '500',
                            family: 'Inter'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#1F2937',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600',
                        family: 'Inter'
                    },
                    bodyFont: {
                        size: 13,
                        family: 'Inter'
                    },
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { 
                        display: true, 
                        text: 'Jumlah Qty',
                        font: {
                            size: 12,
                            weight: '600',
                            family: 'Inter'
                        },
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            family: 'Inter'
                        },
                        color: '#6B7280'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { 
                        drawOnChartArea: false
                    },
                    title: { 
                        display: true, 
                        text: 'Persentase Defect (%)',
                        font: {
                            size: 12,
                            weight: '600',
                            family: 'Inter'
                        },
                        color: '#6B7280'
                    },
                    ticks: {
                        callback: function(value) { 
                            return value + "%"
                        },
                        font: {
                            size: 11,
                            family: 'Inter'
                        },
                        color: '#6B7280'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            family: 'Inter'
                        },
                        color: '#6B7280'
                    }
                }
            }
        }
    });
</script>

</body>
</html>

@endsection