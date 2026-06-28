<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\ClusteringController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\UserController;

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Sensor (index & show: semua; create/store/edit/update/destroy: admin only via view)
    Route::get('/sensor',          [SensorDataController::class, 'index'])->name('sensor.index');
    Route::get('/sensor/create',   [SensorDataController::class, 'create'])->name('sensor.create')->middleware('admin');
    Route::post('/sensor',         [SensorDataController::class, 'store'])->name('sensor.store')->middleware('admin');
    Route::get('/sensor/{sensor}/edit', [SensorDataController::class, 'edit'])->name('sensor.edit')->middleware('admin');
    Route::put('/sensor/{sensor}', [SensorDataController::class, 'update'])->name('sensor.update')->middleware('admin');
    Route::delete('/sensor/{sensor}', [SensorDataController::class, 'destroy'])->name('sensor.destroy')->middleware('admin');

    // Hasil Klasifikasi
    Route::get('/klasifikasi', [KlasifikasiController::class, 'index'])->name('klasifikasi.index');

    // Hasil Clustering
    Route::get('/clustering', [ClusteringController::class, 'index'])->name('clustering.index');

    // Prediksi
    Route::get('/prediksi',  [PrediksiController::class, 'index'])->name('prediksi.index');
    Route::post('/prediksi', [PrediksiController::class, 'predict'])->name('prediksi.predict');

    // Manajemen User (Admin Only)
    Route::middleware('admin')->group(function () {
        Route::get('/users',               [UserController::class, 'index'])->name('users.index');
        Route::post('/users',              [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',        [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',     [UserController::class, 'destroy'])->name('users.destroy');
    });
});
