<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiHasil;

class KlasifikasiSeeder extends Seeder
{
    public function run(): void
    {
        KlasifikasiHasil::truncate();

        // Data dari Classification Report notebook:
        // Test Accuracy: 0.9084
        // Line 1: precision=0.8367, recall=0.9775, f1=0.9017, support=2176
        // Line 2: precision=0.9526, recall=0.8697, f1=0.9093, support=2172
        // Line 3: precision=0.9558, recall=0.8778, f1=0.9151, support=2168

        $data = [
            [
                'line'          => 1,
                'precision_val' => 0.8367,
                'recall_val'    => 0.9775,
                'f1_score'      => 0.9017,
                'support'       => 2176,
                'akurasi'       => 0.9084,
            ],
            [
                'line'          => 2,
                'precision_val' => 0.9526,
                'recall_val'    => 0.8697,
                'f1_score'      => 0.9093,
                'support'       => 2172,
                'akurasi'       => null,
            ],
            [
                'line'          => 3,
                'precision_val' => 0.9558,
                'recall_val'    => 0.8778,
                'f1_score'      => 0.9151,
                'support'       => 2168,
                'akurasi'       => null,
            ],
        ];

        foreach ($data as $row) {
            KlasifikasiHasil::create($row);
        }
    }
}
