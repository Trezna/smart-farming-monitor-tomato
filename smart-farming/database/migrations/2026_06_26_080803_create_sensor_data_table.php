<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu');
            $table->tinyInteger('line');
            $table->decimal('soil_ec', 10, 2);
            $table->decimal('soil_humidity', 6, 2);
            $table->decimal('soil_temperature', 6, 2);
            $table->decimal('co2', 10, 2);
            $table->decimal('air_humidity', 6, 2);
            $table->decimal('pressure', 10, 2);
            $table->decimal('air_temperature', 6, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
