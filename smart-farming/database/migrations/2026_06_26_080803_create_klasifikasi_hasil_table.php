<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_hasil', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('line');
            $table->decimal('precision_val', 6, 4);
            $table->decimal('recall_val', 6, 4);
            $table->decimal('f1_score', 6, 4);
            $table->integer('support');
            $table->decimal('akurasi', 6, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_hasil');
    }
};
