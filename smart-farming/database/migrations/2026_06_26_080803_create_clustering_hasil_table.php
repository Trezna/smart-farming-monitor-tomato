<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clustering_hasil', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('cluster');
            $table->integer('jumlah_data');
            $table->decimal('avg_soil_ec', 10, 2);
            $table->decimal('avg_soil_humidity', 6, 2);
            $table->decimal('avg_soil_temperature', 6, 2);
            $table->decimal('avg_co2', 10, 2);
            $table->decimal('avg_air_humidity', 6, 2);
            $table->decimal('avg_pressure', 10, 2);
            $table->decimal('avg_air_temperature', 6, 2);
            $table->decimal('silhouette_score', 6, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clustering_hasil');
    }
};
