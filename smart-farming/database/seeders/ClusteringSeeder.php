<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClusteringHasil;

class ClusteringSeeder extends Seeder
{
    public function run(): void
    {
        ClusteringHasil::truncate();

        // Data dari K-Means Clustering notebook (K=3, Silhouette=0.2868):
        // Cluster 0: 13665 data
        // Cluster 1: 14036 data
        // Cluster 2:  4878 data
        // Rata-rata fitur per cluster dari output notebook

        $data = [
            [
                'cluster'              => 0,
                'jumlah_data'          => 13665,
                'avg_soil_ec'          => 297.01,
                'avg_soil_humidity'    => 23.46,
                'avg_soil_temperature' => 23.38,
                'avg_co2'              => 514.56,
                'avg_air_humidity'     => 74.34,
                'avg_pressure'         => 1010.16,
                'avg_air_temperature'  => 20.31,
                'silhouette_score'     => 0.2868,
            ],
            [
                'cluster'              => 1,
                'jumlah_data'          => 14036,
                'avg_soil_ec'          => 276.23,
                'avg_soil_humidity'    => 23.17,
                'avg_soil_temperature' => 23.74,
                'avg_co2'              => 449.09,
                'avg_air_humidity'     => 42.53,
                'avg_pressure'         => 1009.34,
                'avg_air_temperature'  => 32.67,
                'silhouette_score'     => null,
            ],
            [
                'cluster'              => 2,
                'jumlah_data'          => 4878,
                'avg_soil_ec'          => 874.52,
                'avg_soil_humidity'    => 39.78,
                'avg_soil_temperature' => 26.34,
                'avg_co2'              => 481.64,
                'avg_air_humidity'     => 57.48,
                'avg_pressure'         => 1010.05,
                'avg_air_temperature'  => 28.80,
                'silhouette_score'     => null,
            ],
        ];

        foreach ($data as $row) {
            ClusteringHasil::create($row);
        }
    }
}
