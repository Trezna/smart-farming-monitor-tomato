@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data sensor & hasil analisis Machine Learning')

@section('content')

{{-- Metric Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:28px;">

    {{-- Total Data --}}
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h6M9 12h6M9 15h4"/>
            </svg>
        </div>
        <div>
            <div class="metric-value" style="font-size:1.75rem;font-weight:700;color:#1a1a1a;">{{ number_format($totalData) }}</div>
            <div class="metric-label">Total Data Sensor</div>
            <div style="font-size:0.75rem;color:#6b7280;margin-top:4px;">32.579 baris CSV</div>
        </div>
    </div>

    {{-- Jumlah Line --}}
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
        </div>
        <div>
            <div class="metric-value" style="font-size:1.75rem;font-weight:700;color:#1a1a1a;">{{ $jumlahLine }}</div>
            <div class="metric-label">Jumlah Line Irigasi</div>
            <div style="font-size:0.75rem;color:#6b7280;margin-top:4px;">Line 1, 2, dan 3</div>
        </div>
    </div>

    {{-- Akurasi RF --}}
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div>
            <div class="metric-value" style="font-size:1.75rem;font-weight:700;color:#3b6d11;">{{ $akurasiRF }}%</div>
            <div class="metric-label">Akurasi Random Forest</div>
            <div style="font-size:0.75rem;color:#6b7280;margin-top:4px;">Test set (n=6.516)</div>
        </div>
    </div>

    {{-- Silhouette Score --}}
    <div class="metric-card">
        <div class="metric-icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
        </div>
        <div>
            <div class="metric-value" style="font-size:1.75rem;font-weight:700;color:#3b6d11;">{{ $silhouetteScore }}</div>
            <div class="metric-label">Silhouette Score (K-Means)</div>
            <div style="font-size:0.75rem;color:#6b7280;margin-top:4px;">K=3 cluster optimal</div>
        </div>
    </div>

</div>

{{-- Rata-rata Sensor Per Line --}}
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:8px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Rata-rata Sensor per Line Irigasi
        </div>
        <a href="{{ route('sensor.index') }}" class="btn btn-secondary btn-sm">
            Lihat Semua Data →
        </a>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>LINE</th>
                        <th>JUMLAH DATA</th>
                        <th>SOIL EC (μS/cm)</th>
                        <th>KELEMBABAN TANAH (%)</th>
                        <th>SUHU TANAH (°C)</th>
                        <th>CO₂ (ppm)</th>
                        <th>KELEMBABAN UDARA (%)</th>
                        <th>TEKANAN (hPa)</th>
                        <th>SUHU UDARA (°C)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avgPerLine as $row)
                    <tr>
                        <td>
                            <span class="badge {{ $row->line == 1 ? 'badge-green' : ($row->line == 2 ? 'badge-blue' : 'badge-yellow') }}">
                                Line {{ $row->line }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($row->jumlah_data) }}</strong></td>
                        <td>{{ $row->avg_soil_ec }}</td>
                        <td>{{ $row->avg_soil_humidity }}%</td>
                        <td>{{ $row->avg_soil_temperature }}°C</td>
                        <td>{{ $row->avg_co2 }}</td>
                        <td>{{ $row->avg_air_humidity }}%</td>
                        <td>{{ $row->avg_pressure }}</td>
                        <td>{{ $row->avg_air_temperature }}°C</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Quick Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:18px;">
    <div class="card">
        <div class="card-header">Model Random Forest</div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Estimator</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">200 pohon</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Max Depth</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">10</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Cross Validation</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">5-Fold Stratified</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-size:0.84rem;color:#6b7280;">CV Accuracy</span>
                    <span style="font-size:0.84rem;font-weight:700;color:#3b6d11;">91.38% ± 0.40%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">K-Means Clustering</div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Jumlah Cluster</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">K = 3</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Cluster 0</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">13.665 data (41.9%)</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Cluster 1</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">14.036 data (43.1%)</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="font-size:0.84rem;color:#6b7280;">Cluster 2</span>
                    <span style="font-size:0.84rem;font-weight:600;color:#1a1a1a;">4.878 data (15.0%)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Akses Cepat</div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('prediksi.index') }}" class="btn btn-primary" style="justify-content:center;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Prediksi Line Irigasi
                </a>
                <a href="{{ route('klasifikasi.index') }}" class="btn btn-secondary" style="justify-content:center;">
                    Lihat Hasil Klasifikasi
                </a>
                <a href="{{ route('clustering.index') }}" class="btn btn-secondary" style="justify-content:center;">
                    Lihat Hasil Clustering
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
