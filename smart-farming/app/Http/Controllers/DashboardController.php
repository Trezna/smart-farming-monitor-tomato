<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\KlasifikasiHasil;
use App\Models\ClusteringHasil;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Metric cards
        $totalData       = SensorData::count();
        $jumlahLine      = SensorData::distinct('line')->count('line');
        $akurasiRF       = 90.84;
        $silhouetteScore = 0.2868;

        // Rata-rata sensor per line
        $avgPerLine = SensorData::select(
            'line',
            DB::raw('ROUND(AVG(soil_ec), 2) as avg_soil_ec'),
            DB::raw('ROUND(AVG(soil_humidity), 2) as avg_soil_humidity'),
            DB::raw('ROUND(AVG(soil_temperature), 2) as avg_soil_temperature'),
            DB::raw('ROUND(AVG(co2), 2) as avg_co2'),
            DB::raw('ROUND(AVG(air_humidity), 2) as avg_air_humidity'),
            DB::raw('ROUND(AVG(pressure), 2) as avg_pressure'),
            DB::raw('ROUND(AVG(air_temperature), 2) as avg_air_temperature'),
            DB::raw('COUNT(*) as jumlah_data')
        )
        ->groupBy('line')
        ->orderBy('line')
        ->get();

        return view('dashboard.index', compact(
            'totalData',
            'jumlahLine',
            'akurasiRF',
            'silhouetteScore',
            'avgPerLine'
        ));
    }
}
