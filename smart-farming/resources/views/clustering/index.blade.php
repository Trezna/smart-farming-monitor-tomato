@extends('layouts.app')

@section('title', 'Hasil Clustering')
@section('page-title', 'Hasil Clustering')
@section('page-subtitle', 'K-Means Clustering — K=3, Silhouette Score: 0.2868')

@section('content')

{{-- Header Banner --}}
<div style="background:linear-gradient(135deg,#3b6d11,#639922);border-radius:16px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <p style="color:rgba(255,255,255,0.7);font-size:0.84rem;font-weight:500;margin-bottom:4px;">K-Means Clustering (Unsupervised Learning)</p>
        <h2 style="color:white;font-size:2rem;font-weight:800;">Silhouette Score: {{ $silhouetteScore }}</h2>
        <p style="color:rgba(255,255,255,0.75);font-size:0.84rem;margin-top:4px;">K=3 | n_init=10 | {{ number_format($totalData) }} data | sample_size=5000 untuk komputasi</p>
    </div>
    <div style="display:flex;gap:16px;flex-wrap:wrap;">
        @foreach($clusters as $c)
        <div style="text-align:center;background:rgba(255,255,255,0.15);border-radius:12px;padding:14px 20px;">
            <div style="color:white;font-size:1.4rem;font-weight:800;">{{ number_format($c->jumlah_data) }}</div>
            <div style="color:rgba(255,255,255,0.7);font-size:0.75rem;">Cluster {{ $c->cluster }}</div>
        </div>
        @endforeach
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Donut Chart --}}
    <div class="card" style="display:flex;flex-direction:column;">
        <div class="card-header">Distribusi Data per Cluster</div>
        <div class="card-body" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div style="position:relative; height: 260px; width: 100%; display:flex; justify-content:center;">
                <canvas id="clusterDonut" style="max-width:280px;"></canvas>
            </div>
            <div style="display:flex;gap:16px;margin-top:16px;flex-wrap:wrap;justify-content:center;">
                <div style="display:flex;align-items:center;gap:6px;font-size:0.82rem;">
                    <span style="width:12px;height:12px;background:#e91e63;border-radius:3px;"></span> Cluster 0 (41.9%)
                </div>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.82rem;">
                    <span style="width:12px;height:12px;background:#2196f3;border-radius:3px;"></span> Cluster 1 (43.1%)
                </div>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.82rem;">
                    <span style="width:12px;height:12px;background:#4caf50;border-radius:3px;"></span> Cluster 2 (15.0%)
                </div>
            </div>
        </div>
    </div>

    {{-- Silhouette Per K Chart --}}
    <div class="card" style="display:flex;flex-direction:column;">
        <div class="card-header">Silhouette Score per Nilai K</div>
        <div class="card-body" style="flex:1;display:flex;flex-direction:column;justify-content:center;">
            <div style="position:relative; height: 260px; width: 100%;">
                <canvas id="silhouetteChart"></canvas>
            </div>
            <div style="margin-top:12px;padding:10px 14px;background:#f0f7e8;border-radius:10px;font-size:0.83rem;color:#3b6d11;font-weight:600;">
                K=3 dipilih karena memiliki Silhouette Score tertinggi (0.2868)
            </div>
        </div>
    </div>

</div>

{{-- Rata-rata Fitur per Cluster --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">Rata-rata Fitur Sensor per Cluster</div>
    <div class="card-body" style="padding:0 0 18px;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>CLUSTER</th>
                        <th>JUMLAH DATA</th>
                        <th>SOIL EC (μS/cm)</th>
                        <th>KELEMBABAN TANAH (%)</th>
                        <th>SUHU TANAH (°C)</th>
                        <th>CO₂ (ppm)</th>
                        <th>KELEMBABAN UDARA (%)</th>
                        <th>TEKANAN (hPa)</th>
                        <th>SUHU UDARA (°C)</th>
                        <th>KARAKTERISTIK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clusters as $c)
                    @php
                        $clusterColors = ['#e91e63','#2196f3','#4caf50'];
                        $color = $clusterColors[$c->cluster];
                        $chars = [
                            0 => 'Lembab, CO₂ Tinggi',
                            1 => 'Kering, Suhu Tinggi',
                            2 => 'EC Tinggi, Basah',
                        ];
                    @endphp
                    <tr>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:6px;font-weight:700;">
                                <span style="width:10px;height:10px;background:{{ $color }};border-radius:50%;flex-shrink:0;"></span>
                                Cluster {{ $c->cluster }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($c->jumlah_data) }}</strong></td>
                        <td>{{ $c->avg_soil_ec }}</td>
                        <td>{{ $c->avg_soil_humidity }}%</td>
                        <td>{{ $c->avg_soil_temperature }}°C</td>
                        <td>{{ $c->avg_co2 }}</td>
                        <td>{{ $c->avg_air_humidity }}%</td>
                        <td>{{ $c->avg_pressure }}</td>
                        <td>{{ $c->avg_air_temperature }}°C</td>
                        <td><span class="badge" style="background:{{ $color }}20;color:{{ $color }};border:1px solid {{ $color }}40;">{{ $chars[$c->cluster] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Cross-tab Line vs Cluster --}}
<div class="card">
    <div class="card-header">Cross-tabulation: Line Irigasi vs Cluster</div>
    <div class="card-body" style="padding:0 0 18px;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Line Irigasi</th>
                        <th>Cluster 0</th>
                        <th>Cluster 1</th>
                        <th>Cluster 2</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crossTab as $row)
                    <tr>
                        <td>
                            <span class="badge {{ $row['line'] == 'Line 1' ? 'badge-green' : ($row['line'] == 'Line 2' ? 'badge-blue' : 'badge-yellow') }}">
                                {{ $row['line'] }}
                            </span>
                        </td>
                        <td>{{ number_format($row['cluster_0']) }}</td>
                        <td>{{ number_format($row['cluster_1']) }}</td>
                        <td>{{ number_format($row['cluster_2']) }}</td>
                        <td><strong>{{ number_format($row['total']) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f0f7e8;">
                        <td style="font-weight:700;padding:12px 14px;">Total</td>
                        <td style="font-weight:700;padding:12px 14px;">13.665</td>
                        <td style="font-weight:700;padding:12px 14px;">14.036</td>
                        <td style="font-weight:700;padding:12px 14px;">4.878</td>
                        <td style="font-weight:700;padding:12px 14px;">32.579</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="padding:14px 22px 0;">
            <div class="alert alert-warning" style="margin-bottom:0;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <strong>Catatan:</strong> Cluster tidak identik dengan Line irigasi karena K-Means tidak menggunakan label. Pengelompokan berdasarkan kemiripan pola sensor 7 fitur.
            </div>
        </div>
    </div>
</div>

<script>
// Donut Chart
const donutCtx = document.getElementById('clusterDonut').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Cluster 0', 'Cluster 1', 'Cluster 2'],
        datasets: [{
            data: [13665, 14036, 4878],
            backgroundColor: ['#e91e63', '#2196f3', '#4caf50'],
            borderColor: ['#fff','#fff','#fff'],
            borderWidth: 3,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.label}: ${ctx.raw.toLocaleString()} data (${((ctx.raw/32579)*100).toFixed(1)}%)`
                }
            }
        },
        cutout: '65%',
    }
});

// Silhouette per K Chart
const silCtx = document.getElementById('silhouetteChart').getContext('2d');
const kValues = @json(array_keys($silhouettePerK));
const silScores = @json(array_values($silhouettePerK));

new Chart(silCtx, {
    type: 'line',
    data: {
        labels: kValues.map(k => 'K='+k),
        datasets: [{
            label: 'Silhouette Score',
            data: silScores,
            borderColor: '#3b6d11',
            backgroundColor: 'rgba(59,109,17,0.1)',
            borderWidth: 2.5,
            pointRadius: 5,
            pointBackgroundColor: silScores.map((_, i) => kValues[i] == 3 ? '#639922' : '#3b6d11'),
            pointRadius: silScores.map((_, i) => kValues[i] == 3 ? 8 : 5),
            fill: true,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` Score: ${ctx.raw.toFixed(4)}`
                }
            }
        },
        scales: {
            y: {
                min: 0.20, max: 0.30,
                grid: { color: '#f0f0f0' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>

@endsection
