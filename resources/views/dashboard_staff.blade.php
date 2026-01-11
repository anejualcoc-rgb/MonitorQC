@extends('layouts.app')


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
        // safe helpers
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
    

    <div class="download-box mb-4">
        <a href="{{ route('export') }}" class="download-btn">
            <i class="bi bi-download"></i> Download Data Excel
        </a>
    </div>

    <style>
        .download-box {
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            display: inline-block;
        }

        .download-btn {
            background-color: #015255ff;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }

        .download-btn:hover {
            background-color: #015255ff;
            text-decoration: none;
            color: white;
        }
    </style>

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
            <div style="padding: 1rem;">
                @foreach($distribusiData as $i => $val)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
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
                            <span class="{{ $badgeClass }}">{{ number_format($achievement, 2) }}%</span>
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
                        <th>Model Sepatu</th>
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
                            <span class="{{ $severityBadge }}">{{ $defect->Severity }}</span>
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
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
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
            cutout: '70%'
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
            maintainAspectRatio: false
        }
    });

    // Top defect types (horizontal bar)
    const defectTypes = {};
    defectData.forEach(d => {
        defectTypes[d.Jenis_Defect] = (defectTypes[d.Jenis_Defect] || 0) + Number(d.Jumlah_Cacat_perjenis || 0);
    });
    const sorted = Object.entries(defectTypes).sort((a,b)=>b[1]-a[1]).slice(0,10);
    new Chart(document.getElementById('defectTypeChart'), {
        type: 'bar',
        data: {
            labels: sorted.map(s=>s[0]),
            datasets: [{ data: sorted.map(s=>s[1]), backgroundColor: '#ef4444' }]
        },
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
    });

    // Severity chart
    const severityCounts = {};
    defectData.forEach(d => {
        severityCounts[d.Severity] = (severityCounts[d.Severity] || 0) + Number(d.Jumlah_Cacat_perjenis || 0);
    });
    new Chart(document.getElementById('severityChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(severityCounts),
            datasets: [{ data: Object.values(severityCounts), backgroundColor: ['#ef4444','#f59e0b','#0dcaf0'] }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '70%' }
    });

    // Line chart
    const lineData = {};
    produksiData.forEach(d=> {
        lineData[d.Line_Produksi] = (lineData[d.Line_Produksi]||0) + Number(d.Jumlah_Produksi);
    });
    new Chart(document.getElementById('lineChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(lineData),
            datasets: [{ data: Object.values(lineData), backgroundColor: ['#0d6efd','#6610f2','#6f42c1','#d63384'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endpush
