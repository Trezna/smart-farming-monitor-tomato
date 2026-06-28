<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        // Path absolut ke CSV dataset di d:\FrameworkUAS\
        $basePath     = rtrim(str_replace('smart-farming', '', str_replace('\\', '/', base_path())), '/');
        $csvPath      = str_replace('/', DIRECTORY_SEPARATOR, $basePath . '/dataset_final_tomat_irigasi.csv');

        // Fallback langsung ke hardcode jika perlu
        if (!file_exists($csvPath)) {
            $csvPath = 'D:\\FrameworkUAS\\dataset_final_tomat_irigasi.csv';
        }

        if (!file_exists($csvPath)) {
            $this->command->warn("CSV tidak ditemukan di: $csvPath");
            $this->command->warn("Pastikan file dataset_final_tomat_irigasi.csv ada di folder d:\\FrameworkUAS\\");
            return;
        }

        DB::table('sensor_data')->truncate();

        $this->command->info("Memulai import data sensor dari CSV...");

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle); // skip header

        $batch     = [];
        $batchSize = 500;
        $total     = 0;
        $now       = now()->toDateTimeString();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 9) continue;

            // Parse waktu — hilangkan milliseconds jika ada
            $waktu = preg_replace('/\.\d+$/', '', $row[0]);

            $batch[] = [
                'waktu'            => $waktu,
                'line'             => (int) $row[1],
                'soil_ec'          => (float) $row[2],
                'soil_humidity'    => (float) $row[3],
                'soil_temperature' => (float) $row[4],
                'co2'              => (float) $row[5],
                'air_humidity'     => (float) $row[6],
                'pressure'         => (float) $row[7],
                'air_temperature'  => (float) $row[8],
                'created_at'       => $now,
                'updated_at'       => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('sensor_data')->insert($batch);
                $total += count($batch);
                $batch  = [];
                $this->command->info("  Inserted $total rows...");
            }
        }

        // Insert sisa
        if (!empty($batch)) {
            DB::table('sensor_data')->insert($batch);
            $total += count($batch);
        }

        fclose($handle);
        $this->command->info("✅ Import selesai: $total baris berhasil dimasukkan.");
    }
}
