@extends('layouts.app_manager')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Per Line</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Desain dari request sebelumnya */
        :root {
            --primary-color: #015255;
            --line1-color: #6366f1; /* Indigo - Line 1 */
            --line2-color: #f59e0b; /* Amber - Line 2 */
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
        }
        
        body { background: var(--bg-gradient); min-height: 100vh; }

        .card {
            border-radius: 16px; border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-4px); }

        /* Card Header Spesifik Line */
        .header-line1 { border-left: 5px solid var(--line1-color); }
        .header-line2 { border-left: 5px solid var(--line2-color); }

        .line-badge {
            padding: 5px 12px; border-radius: 20px; color: white; font-weight: bold; font-size: 0.85rem;
        }
        .bg-line1 { background-color: var(--line1-color); }
        .bg-line2 { background-color: var(--line2-color); }

        .stat-label { font-size: 0.8rem; color: #6b7280; font-weight: 600; text-transform: uppercase; margin-bottom: 5px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
        
        .section-title {
            color: var(--primary-color); font-weight: 700; margin-bottom: 1.5rem;
            border-bottom: 3px solid var(--primary-color); display: inline-block; padding-bottom: 5px;
        }

        /* Filter Styles */
        .filter-group {
            background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex; gap: 10px; align-items: center;
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">⚖️ Analisis Komparasi Line</h2>
            <p class="text-muted mb-0">Perbandingan performa Line 1 vs Line 2</p>
        </div>
        
        <form action="{{ url()->current() }}" method="GET" class="filter-group">
            
            <select name="filter" id="filterType" class="form-select border-0 bg-light" style="width: auto; font-weight: 600;" onchange="toggleFilter(this.value)">
                <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="weekly" {{ $filterType == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="all" {{ $filterType == 'all' ? 'selected' : '' }}>All Time</option>
            </select>

            <input type="month" name="month" id="monthInput" class="form-control border-0 shadow-sm" 
                   value="{{ $monthInput }}" 
                   style="display: {{ $filterType == 'monthly' ? 'block' : 'none' }};">

            <input type="week" name="week" id="weekInput" class="form-control border-0 shadow-sm" 
                   value="{{ $weekInput }}" 
                   style="display: {{ $filterType == 'weekly' ? 'block' : 'none' }};">

            <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="mb-4">
        <h4 class="section-title">Head-to-Head: {{ $dateLabel }}</h4>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 header-line1 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="line-badge bg-line1">LINE 1</span>
                    <i class="bi bi-gear-wide-connected text-muted fs-4"></i>
                </div>
                
                <div class="row g-3 text-center">
                    <div class="col-4 border-end">
                        <div class="stat-label">Produksi</div>
                        <div class="stat-value" style="color: var(--line1-color);">{{ number_format($dataLine1['produksi']) }}</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="stat-label">Achv %</div>
                        <div class="stat-value {{ $dataLine1['achv'] >= 100 ? 'text-success' : 'text-danger' }}">
                            {{ $dataLine1['achv'] }}%
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-label">Defect %</div>
                        <div class="stat-value {{ $dataLine1['rate'] > 2 ? 'text-danger' : 'text-success' }}">
                            {{ $dataLine1['rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 header-line2 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="line-badge bg-line2">LINE 2</span>
                    <i class="bi bi-gear-wide-connected text-muted fs-4"></i>
                </div>
                
                <div class="row g-3 text-center">
                    <div class="col-4 border-end">
                        <div class="stat-label">Produksi</div>
                        <div class="stat-value" style="color: var(--line2-color);">{{ number_format($dataLine2['produksi']) }}</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="stat-label">Achv %</div>
                        <div class="stat-value {{ $dataLine2['achv'] >= 100 ? 'text-success' : 'text-danger' }}">
                            {{ $dataLine2['achv'] }}%
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-label">Defect %</div>
                        <div class="stat-value {{ $dataLine2['rate'] > 2 ? 'text-danger' : 'text-success' }}">
                            {{ $dataLine2['rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 mb-4"><h4 class="section-title">Tren Produktivitas</h4></div>

    <div class="card p-4 shadow-sm">
        <div style="height: 400px;">
            <canvas id="comparisonChart"></canvas>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-md-12">
            <div class="card p-4 bg-light border-0">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightbulb-fill text-warning me-2"></i> Insight Otomatis</h6>
                <ul class="mb-0 text-muted">
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
    // Toggle input filter
    function toggleFilter(value) {
        document.getElementById('monthInput').style.display = (value === 'monthly') ? 'block' : 'none';
        document.getElementById('weekInput').style.display = (value === 'weekly') ? 'block' : 'none';
    }

    // Chart Configuration
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Line 1',
                    data: @json($chartLine1),
                    borderColor: '#6366f1', // Warna Line 1
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Line 2',
                    data: @json($chartLine2),
                    borderColor: '#f59e0b', // Warna Line 2
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5] },
                    title: { display: true, text: 'Jumlah Produksi' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

</body>
</html>
@endsection