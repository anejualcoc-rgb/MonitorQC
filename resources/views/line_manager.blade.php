@extends('layouts.app_manager')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Per Line</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #015255;
            --line1-color: #6366f1;
            --line2-color: #f59e0b;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
        }
        
        body { 
            background: var(--bg-gradient); 
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        .card {
            border-radius: 16px; 
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        @media (min-width: 768px) {
            .card:hover { transform: translateY(-4px); }
        }

        .header-line1 { border-left: 5px solid var(--line1-color); }
        .header-line2 { border-left: 5px solid var(--line2-color); }

        .line-badge {
            padding: 5px 12px; 
            border-radius: 20px; 
            color: white; 
            font-weight: bold; 
            font-size: 0.85rem;
        }
        .bg-line1 { background-color: var(--line1-color); }
        .bg-line2 { background-color: var(--line2-color); }

        .stat-label { 
            font-size: 0.75rem; 
            color: #6b7280; 
            font-weight: 600; 
            text-transform: uppercase; 
            margin-bottom: 5px;
        }
        
        .stat-value { 
            font-size: 1.25rem; 
            font-weight: 700; 
            color: #1f2937;
        }
        
        @media (min-width: 768px) {
            .stat-label { font-size: 0.8rem; }
            .stat-value { font-size: 1.5rem; }
        }
        
        .section-title {
            color: var(--primary-color); 
            font-weight: 700; 
            margin-bottom: 1.5rem;
            border-bottom: 3px solid var(--primary-color); 
            display: inline-block; 
            padding-bottom: 5px;
            font-size: 1.1rem;
        }
        
        @media (min-width: 768px) {
            .section-title { font-size: 1.25rem; }
        }

        .filter-group {
            background: white; 
            padding: 12px; 
            border-radius: 10px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .filter-group {
                flex-direction: row;
                align-items: center;
                width: auto;
            }
        }
        
        .filter-group select {
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .filter-group select {
                width: 150px;
            }
        }
        
        .filter-group input {
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .filter-group input {
                width: 180px;
            }
        }
        
        .filter-group button {
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .filter-group button {
                width: auto;
            }
        }
        
        .filter-inputs-wrapper {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .filter-inputs-wrapper {
                display: contents;
            }
        }

        .page-header {
            margin-bottom: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .page-header {
                margin-bottom: 3rem;
            }
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .page-header h2 {
                font-size: 2rem;
            }
        }

        .chart-container {
            height: 300px;
            position: relative;
        }
        
        @media (min-width: 768px) {
            .chart-container {
                height: 400px;
            }
        }
        
        @media (min-width: 1200px) {
            .chart-container {
                height: 450px;
            }
        }

        .insight-box {
            font-size: 0.9rem;
        }
        
        .insight-box li {
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 767px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .stat-divider {
                border-right: none !important;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 0.75rem;
                margin-bottom: 0.75rem;
            }
            
            .stat-divider:last-child {
                border-bottom: none;
                margin-bottom: 0;
                padding-bottom: 0;
            }
        }
    </style>
</head>
<body>

<div class="container py-3 py-md-5">
    
    <div class="d-flex justify-content-between align-items-start page-header flex-column flex-md-row gap-3">
        <div class="w-100 w-md-auto">
            <h2 class="fw-bold text-dark mb-1">Filter Work Line</h2>
            <p class="text-muted mb-0 small">Perbandingan performa Line 1 vs Line 2</p>
        </div>
        
        <form action="{{ url()->current() }}" method="GET" class="filter-group w-100 w-md-auto">
            
            <select name="filter" id="filterType" class="form-select border-0 bg-light" style="font-weight: 600;" onchange="toggleFilter(this.value)">
                <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly" {{ $filterType == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="all" {{ $filterType == 'all' ? 'selected' : '' }}>All Time</option>
            </select>

            <div class="filter-inputs-wrapper">
                <input type="month" name="month" id="monthInput" class="form-control border-0 shadow-sm" 
                       value="{{ $monthInput }}" 
                       style="display: {{ $filterType == 'monthly' ? 'block' : 'none' }};">

                <input type="week" name="week" id="weekInput" class="form-control border-0 shadow-sm" 
                       value="{{ $weekInput }}" 
                       style="display: {{ $filterType == 'weekly' ? 'block' : 'none' }};">

                <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">
                    <i class="bi bi-search"></i> <span class="d-none d-md-inline">Filter</span>
                </button>
            </div>
        </form>
    </div>

    <div class="mb-3 mb-md-4">
        <h4 class="section-title">Head-to-Head: {{ $dateLabel }}</h4>
    </div>

    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-6">
            <div class="card p-3 p-md-4 header-line1 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                    <span class="line-badge bg-line1">LINE 1</span>
                    <i class="bi bi-gear-wide-connected text-muted fs-4"></i>
                </div>
                
                <div class="row g-3 text-center">
                    <div class="col-12 col-md-4 stat-divider">
                        <div class="stat-label">Produksi</div>
                        <div class="stat-value" style="color: var(--line1-color);">{{ number_format($dataLine1['produksi']) }}</div>
                    </div>
                    <div class="col-6 col-md-4 stat-divider">
                        <div class="stat-label">Achv %</div>
                        <div class="stat-value {{ $dataLine1['achv'] >= 100 ? 'text-success' : 'text-danger' }}">
                            {{ $dataLine1['achv'] }}%
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-label">Defect %</div>
                        <div class="stat-value {{ $dataLine1['rate'] > 2 ? 'text-danger' : 'text-success' }}">
                            {{ $dataLine1['rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card p-3 p-md-4 header-line2 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                    <span class="line-badge bg-line2">LINE 2</span>
                    <i class="bi bi-gear-wide-connected text-muted fs-4"></i>
                </div>
                
                <div class="row g-3 text-center">
                    <div class="col-12 col-md-4 stat-divider">
                        <div class="stat-label">Produksi</div>
                        <div class="stat-value" style="color: var(--line2-color);">{{ number_format($dataLine2['produksi']) }}</div>
                    </div>
                    <div class="col-6 col-md-4 stat-divider">
                        <div class="stat-label">Achv %</div>
                        <div class="stat-value {{ $dataLine2['achv'] >= 100 ? 'text-success' : 'text-danger' }}">
                            {{ $dataLine2['achv'] }}%
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-label">Defect %</div>
                        <div class="stat-value {{ $dataLine2['rate'] > 2 ? 'text-danger' : 'text-success' }}">
                            {{ $dataLine2['rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 mt-md-5 mb-3 mb-md-4">
        <h4 class="section-title">Tren Produktivitas</h4>
    </div>

    <div class="card p-3 p-md-4 shadow-sm">
        <div class="chart-container">
            <canvas id="comparisonChart"></canvas>
        </div>
    </div>

    <div class="row mt-3 mt-md-4 g-3 g-md-4">
        <div class="col-12">
            <div class="card p-3 p-md-4 bg-light border-0">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-lightbulb-fill text-warning me-2"></i> Insight Otomatis
                </h6>
                <ul class="mb-0 text-muted insight-box">
                    @if($dataLine1['produksi'] > $dataLine2['produksi'])
                        <li>🏆 <b>Line 1</b> memimpin total produksi dengan selisih <b>{{ number_format($dataLine1['produksi'] - $dataLine2['produksi']) }}</b> unit.</li>
                    @elseif($dataLine2['produksi'] > $dataLine1['produksi'])
                        <li>🏆 <b>Line 2</b> memimpin total produksi dengan selisih <b>{{ number_format($dataLine2['produksi'] - $dataLine1['produksi']) }}</b> unit.</li>
                    @else
                        <li>⚖️ Kedua Line memiliki jumlah produksi yang sama.</li>
                    @endif

                    @if($dataLine1['rate'] < $dataLine2['rate'])
                        <li>✅ <b>Line 1</b> memiliki kualitas lebih baik (Defect Rate lebih rendah).</li>
                    @elseif($dataLine2['rate'] < $dataLine1['rate'])
                        <li>✅ <b>Line 2</b> memiliki kualitas lebih baik (Defect Rate lebih rendah).</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

</div>

<script>
    function toggleFilter(value) {
        const monthInput = document.getElementById('monthInput');
        const weekInput = document.getElementById('weekInput');
        
        monthInput.style.display = 'none';
        weekInput.style.display = 'none';
        
        if (value === 'monthly') {
            monthInput.style.display = 'block';
        } else if (value === 'weekly') {
            weekInput.style.display = 'block';
        }
    }

    const ctx = document.getElementById('comparisonChart').getContext('2d');
    
    // Responsive font sizes
    const isMobile = window.innerWidth < 768;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Line 1',
                    data: @json($chartLine1),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                },
                {
                    label: 'Line 2',
                    data: @json($chartLine2),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: isMobile ? 3 : 4,
                    pointHoverRadius: isMobile ? 5 : 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { 
                mode: 'index', 
                intersect: false 
            },
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        font: {
                            size: isMobile ? 11 : 12
                        },
                        padding: isMobile ? 10 : 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: isMobile ? 8 : 12,
                    titleFont: { size: isMobile ? 12 : 14 },
                    bodyFont: { size: isMobile ? 11 : 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { 
                        borderDash: [5, 5] 
                    },
                    ticks: {
                        font: {
                            size: isMobile ? 10 : 11
                        }
                    },
                    title: { 
                        display: !isMobile, 
                        text: 'Jumlah Produksi',
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: {
                            size: isMobile ? 9 : 11
                        },
                        maxRotation: isMobile ? 45 : 0,
                        minRotation: isMobile ? 45 : 0
                    }
                }
            }
        }
    });
</script>

</body>
</html>
@endsection