<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';

    protected $fillable = [
        'waktu',
        'line',
        'soil_ec',
        'soil_humidity',
        'soil_temperature',
        'co2',
        'air_humidity',
        'pressure',
        'air_temperature',
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'line' => 'integer',
        'soil_ec' => 'float',
        'soil_humidity' => 'float',
        'soil_temperature' => 'float',
        'co2' => 'float',
        'air_humidity' => 'float',
        'pressure' => 'float',
        'air_temperature' => 'float',
    ];
}
