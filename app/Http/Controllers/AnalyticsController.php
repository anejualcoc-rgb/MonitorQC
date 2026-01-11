<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduksi;
use App\Models\DataDefect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function periode(Request $request)
    {
        // 1. Ambil input periode dari request (default: daily)
        $periode = $request->input('period', 'daily');

        // 2. Tentukan Format Tanggal SQL berdasarkan database driver (MySQL support)
        switch ($periode) {
            case 'weekly':
                $dateFormat = "%Y-%u"; 
                $labelFormat = "Minggu ke-%u, %Y";
                break;
            case 'monthly':
                $dateFormat = "%Y-%m"; 
                $labelFormat = "%M %Y";
                break;
            case 'daily':
            default:
                $dateFormat = "%Y-%m-%d";
                $labelFormat = "%d %M %Y";
                break;
        }

        // 3. Query Agregasi Data Produksi
        $productionQuery = DataProduksi::select(
                DB::raw("DATE_FORMAT(Tanggal_Produksi, '$dateFormat') as date_group"),
                DB::raw("MAX(Tanggal_Produksi) as raw_date"),
                DB::raw('SUM(Jumlah_Produksi) as total_produksi'),
                DB::raw('SUM(Target_Produksi) as total_target'),
                DB::raw('SUM(Jumlah_Produksi_Cacat) as total_cacat')
            )
            ->groupBy('date_group')
            ->orderBy('date_group', 'asc')
            ->get();

        // 4. Siapkan Data untuk ChartJS
        $chartLabels = $productionQuery->map(function($item) use ($periode) {
            return \Carbon\Carbon::parse($item->raw_date)->translatedFormat($periode == 'monthly' ? 'F Y' : ($periode == 'daily' ? 'd M Y' : 'W, Y'));
        });

        $dataProduksi = $productionQuery->pluck('total_produksi');
        $dataTarget   = $productionQuery->pluck('total_target');
        $dataCacat    = $productionQuery->pluck('total_cacat');
        
        $dataDefectRate = $productionQuery->map(function($item) {
            return $item->total_produksi > 0 
                ? round(($item->total_cacat / $item->total_produksi) * 100, 2) 
                : 0;
        });

        // 5. Query Ringkasan Defect
        $topDefects = DataDefect::select('Jenis_Defect', DB::raw('SUM(Jumlah_Cacat_perjenis) as total'))
            ->groupBy('Jenis_Defect')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 6. TAMBAHAN: Query untuk Card Satuan (Hari Ini, Kemarin, dll)
        $periodCards = $this->getPeriodCards($periode);

        return view('periode_manager', compact(
            'periode',
            'chartLabels',
            'dataProduksi',
            'dataTarget',
            'dataCacat',
            'dataDefectRate',
            'topDefects',
            'periodCards'
        ));
    }

    private function getPeriodCards($periode)
    {
        $cards = [];

        switch ($periode) {
            case 'daily':
                // Hari Ini
                $today = Carbon::today();
                $cards[] = [
                    'label' => 'Hari Ini',
                    'date' => $today->translatedFormat('d M Y'),
                    'data' => $this->getDailyData($today),
                    'badge' => 'primary'
                ];

                // Kemarin
                $yesterday = Carbon::yesterday();
                $cards[] = [
                    'label' => 'Kemarin',
                    'date' => $yesterday->translatedFormat('d M Y'),
                    'data' => $this->getDailyData($yesterday),
                    'badge' => 'secondary'
                ];

                // 2 Hari Lalu
                $twoDaysAgo = Carbon::today()->subDays(2);
                $cards[] = [
                    'label' => '2 Hari Lalu',
                    'date' => $twoDaysAgo->translatedFormat('d M Y'),
                    'data' => $this->getDailyData($twoDaysAgo),
                    'badge' => 'secondary'
                ];
                break;

            case 'weekly':
                // Minggu Ini
                $thisWeek = Carbon::now()->startOfWeek();
                $cards[] = [
                    'label' => 'Minggu Ini',
                    'date' => 'Minggu ke-' . $thisWeek->weekOfYear . ', ' . $thisWeek->year,
                    'data' => $this->getWeeklyData($thisWeek),
                    'badge' => 'primary'
                ];

                // Minggu Lalu
                $lastWeek = Carbon::now()->subWeek()->startOfWeek();
                $cards[] = [
                    'label' => 'Minggu Lalu',
                    'date' => 'Minggu ke-' . $lastWeek->weekOfYear . ', ' . $lastWeek->year,
                    'data' => $this->getWeeklyData($lastWeek),
                    'badge' => 'secondary'
                ];

                // 2 Minggu Lalu
                $twoWeeksAgo = Carbon::now()->subWeeks(2)->startOfWeek();
                $cards[] = [
                    'label' => '2 Minggu Lalu',
                    'date' => 'Minggu ke-' . $twoWeeksAgo->weekOfYear . ', ' . $twoWeeksAgo->year,
                    'data' => $this->getWeeklyData($twoWeeksAgo),
                    'badge' => 'secondary'
                ];
                break;

            case 'monthly':
                // Bulan Ini
                $thisMonth = Carbon::now()->startOfMonth();
                $cards[] = [
                    'label' => 'Bulan Ini',
                    'date' => $thisMonth->translatedFormat('F Y'),
                    'data' => $this->getMonthlyData($thisMonth),
                    'badge' => 'primary'
                ];

                // Bulan Lalu
                $lastMonth = Carbon::now()->subMonth()->startOfMonth();
                $cards[] = [
                    'label' => 'Bulan Lalu',
                    'date' => $lastMonth->translatedFormat('F Y'),
                    'data' => $this->getMonthlyData($lastMonth),
                    'badge' => 'secondary'
                ];

                // 2 Bulan Lalu
                $twoMonthsAgo = Carbon::now()->subMonths(2)->startOfMonth();
                $cards[] = [
                    'label' => '2 Bulan Lalu',
                    'date' => $twoMonthsAgo->translatedFormat('F Y'),
                    'data' => $this->getMonthlyData($twoMonthsAgo),
                    'badge' => 'secondary'
                ];
                break;
        }

        return $cards;
    }

    private function getDailyData($date)
    {
        $data = DataProduksi::whereDate('Tanggal_Produksi', $date)
            ->selectRaw('
                SUM(Jumlah_Produksi) as total_produksi,
                SUM(Target_Produksi) as total_target,
                SUM(Jumlah_Produksi_Cacat) as total_cacat
            ')
            ->first();

        return $this->formatCardData($data);
    }

    private function getWeeklyData($weekStart)
    {
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        $data = DataProduksi::whereBetween('Tanggal_Produksi', [$weekStart, $weekEnd])
            ->selectRaw('
                SUM(Jumlah_Produksi) as total_produksi,
                SUM(Target_Produksi) as total_target,
                SUM(Jumlah_Produksi_Cacat) as total_cacat
            ')
            ->first();

        return $this->formatCardData($data);
    }

    private function getMonthlyData($monthStart)
    {
        $monthEnd = $monthStart->copy()->endOfMonth();
        
        $data = DataProduksi::whereBetween('Tanggal_Produksi', [$monthStart, $monthEnd])
            ->selectRaw('
                SUM(Jumlah_Produksi) as total_produksi,
                SUM(Target_Produksi) as total_target,
                SUM(Jumlah_Produksi_Cacat) as total_cacat
            ')
            ->first();

        return $this->formatCardData($data);
    }

    private function formatCardData($data)
    {
        if (!$data) {
            return [
                'produksi' => 0,
                'target' => 0,
                'cacat' => 0,
                'defect_rate' => 0,
                'achievement' => 0
            ];
        }

        $defectRate = $data->total_produksi > 0 
            ? round(($data->total_cacat / $data->total_produksi) * 100, 2) 
            : 0;

        $achievement = $data->total_target > 0 
            ? round(($data->total_produksi / $data->total_target) * 100, 2) 
            : 0;

        return [
            'produksi' => $data->total_produksi ?? 0,
            'target' => $data->total_target ?? 0,
            'cacat' => $data->total_cacat ?? 0,
            'defect_rate' => $defectRate,
            'achievement' => $achievement
        ];
    }

   public function line(Request $request)
    {
        // 1. Ambil Filter dari Request
        $filterType = $request->input('filter', 'monthly'); // all, monthly, weekly
        $monthInput = $request->input('month', date('Y-m'));
        $weekInput  = $request->input('week', date('Y-\WW')); // Format 2024-W05

        // 2. Tentukan Range Tanggal Berdasarkan Filter
        $query = DataProduksi::query();
        $dateLabel = '';

        if ($filterType == 'all') {
            $dateLabel = 'All Time Data';
            // Tidak ada whereBetween, ambil semua
        } 
        elseif ($filterType == 'weekly') {
            $date = Carbon::parse($weekInput); // Parse week string
            $start = $date->startOfWeek();
            $end   = $date->endOfWeek();
            $query->whereBetween('Tanggal_Produksi', [$start, $end]);
            $dateLabel = 'Minggu ke-' . $date->weekOfYear . ', ' . $date->year;
        } 
        else { // Default: Monthly
            $date = Carbon::parse($monthInput);
            $start = $date->startOfMonth();
            $end   = $date->endOfMonth();
            $query->whereBetween('Tanggal_Produksi', [$start, $end]);
            $dateLabel = $date->translatedFormat('F Y');
        }

        // 3. Clone Query untuk data masing-masing Line agar tidak bentrok
        $qLine1 = clone $query;
        $qLine2 = clone $query;

        // 4. Ambil Data Summary Line 1 & Line 2
        $dataLine1 = $this->getLineData($qLine1, 'Line 1');
        $dataLine2 = $this->getLineData($qLine2, 'Line 2');

        // 5. Data Chart (Trend Harian)
        // Kita perlu query ulang dengan grouping date
        $trendQuery = clone $query; 
        $trendData = $trendQuery->select(
                DB::raw('DATE(Tanggal_Produksi) as date'),
                'Line_Produksi',
                DB::raw('SUM(Jumlah_Produksi) as total')
            )
            ->groupBy('date', 'Line_Produksi')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        $chartLabels = $trendData->keys()->map(function($d){ 
            return Carbon::parse($d)->format('d M'); 
        });

        $chartLine1 = $chartLabels->map(function($label, $key) use ($trendData) {
            $dateKey = $trendData->keys()[$key];
            return $trendData[$dateKey]->where('Line_Produksi', 'Line 1')->sum('total');
        });

        $chartLine2 = $chartLabels->map(function($label, $key) use ($trendData) {
            $dateKey = $trendData->keys()[$key];
            return $trendData[$dateKey]->where('Line_Produksi', 'Line 2')->sum('total');
        });

        return view('line_manager', compact(
            'filterType', 'monthInput', 'weekInput', 'dateLabel',
            'dataLine1', 'dataLine2',
            'chartLabels', 'chartLine1', 'chartLine2'
        ));
    }

    private function getLineData($query, $lineName)
    {
        // Clone query agar 'where' sebelumnya tidak hilang
        $data = $query->where('Line_Produksi', $lineName)
            ->selectRaw('
                SUM(Jumlah_Produksi) as produksi,
                SUM(Target_Produksi) as target,
                SUM(Jumlah_Produksi_Cacat) as cacat
            ')
            ->first();

        $produksi = $data->produksi ?? 0;
        $target   = $data->target ?? 0;
        $cacat    = $data->cacat ?? 0;

        return [
            'produksi' => $produksi,
            'target'   => $target,
            'cacat'    => $cacat,
            'achv'     => $target > 0 ? round(($produksi / $target) * 100, 1) : 0,
            'rate'     => $produksi > 0 ? round(($cacat / $produksi) * 100, 2) : 0,
        ];
    }
}