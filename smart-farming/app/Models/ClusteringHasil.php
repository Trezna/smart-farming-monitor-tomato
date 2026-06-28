<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClusteringHasil extends Model
{
    use HasFactory;

    protected $table = 'clustering_hasil';

    protected $fillable = [
        'cluster',
        'jumlah_data',
        'avg_soil_ec',
        'avg_soil_humidity',
        'avg_soil_temperature',
        'avg_co2',
        'avg_air_humidity',
        'avg_pressure',
        'avg_air_temperature',
        'silhouette_score',
    ];

    protected $casts = [
        'cluster' => 'integer',
        'jumlah_data' => 'integer',
        'avg_soil_ec' => 'float',
        'avg_soil_humidity' => 'float',
        'avg_soil_temperature' => 'float',
        'avg_co2' => 'float',
        'avg_air_humidity' => 'float',
        'avg_pressure' => 'float',
        'avg_air_temperature' => 'float',
        'silhouette_score' => 'float',
    ];
}
