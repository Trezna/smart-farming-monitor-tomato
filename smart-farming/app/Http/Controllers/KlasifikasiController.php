<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiHasil;

class KlasifikasiController extends Controller
{
    public function index()
    {
        $hasil = KlasifikasiHasil::orderBy('line')->get();
        $akurasi = 90.84;

        // Feature importance dari notebook Random Forest
        $featureImportance = [
            ['fitur' => 'soil_humidity',    'importance' => 0.4099, 'label' => 'Kelembaban Tanah'],
            ['fitur' => 'soil_ec',          'importance' => 0.3027, 'label' => 'EC Tanah'],
            ['fitur' => 'soil_temperature', 'importance' => 0.1355, 'label' => 'Suhu Tanah'],
            ['fitur' => 'pressure',         'importance' => 0.0804, 'label' => 'Tekanan Udara'],
            ['fitur' => 'air_temperature',  'importance' => 0.0301, 'label' => 'Suhu Udara'],
            ['fitur' => 'air_humidity',     'importance' => 0.0274, 'label' => 'Kelembaban Udara'],
            ['fitur' => 'co2',              'importance' => 0.0140, 'label' => 'CO2'],
        ];

        // Cross-validation scores
        $cvScores = [0.9141, 0.9075, 0.9179, 0.9114, 0.9183];
        $cvMean   = 0.9138;
        $cvStd    = 0.0040;

        return view('klasifikasi.index', compact(
            'hasil',
            'akurasi',
            'featureImportance',
            'cvScores',
            'cvMean',
            'cvStd'
        ));
    }
}
