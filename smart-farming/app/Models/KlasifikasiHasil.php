<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KlasifikasiHasil extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi_hasil';

    protected $fillable = [
        'line',
        'precision_val',
        'recall_val',
        'f1_score',
        'support',
        'akurasi',
    ];

    protected $casts = [
        'line' => 'integer',
        'precision_val' => 'float',
        'recall_val' => 'float',
        'f1_score' => 'float',
        'support' => 'integer',
        'akurasi' => 'float',
    ];
}
