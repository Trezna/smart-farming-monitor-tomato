@extends('layouts.app')

@section('title', 'Hasil Klasifikasi')
@section('page-title', 'Hasil Klasifikasi')
@section('page-subtitle', 'Random Forest Classification Report — Akurasi 90.84%')

@section('content')

{{-- Accuracy Banner --}}
<div style="background:linear-gradient(135deg,#3b6d11,#4a8915);border-radius:16px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <p style="color:rgba(255,255,255,0.7);font-size:0.84rem;font-weight:500;margin-bottom:4px;">Random Forest Classifier</p>
        <h2 style="color:white;font-size:2rem;font-weight:800;">Akurasi {{ $akurasi }}%</h2>
        <p style="color:rgba(255,255,255,0.75);font-size:0.84rem;margin-top:4px;">n_estimators=200 | max_depth=10 | Test set: 6.516 sampel</p>
    </div>
    <div style="display:flex;gap:16px;flex-wrap:wrap;">
        <div style="text-align:center;background:rgba(255,255,255,0.15);border-radius:12px;padding:14px 20px;">
            <div style="color:white;font-size:1.4rem;font-weight:800;">{{ number_format($cvMean*100, 2) }}%</div>
            <div style="color:rgba(255,255,255,0.7);font-size:0.75rem;">CV Accuracy</div>
        </div>
        <div style="text-align:center;background:rgba(255,255,255,0.15);border-radius:12px;padding:14px 20px;">
            <div style="color:white;font-size:1.4rem;font-weight:800;">5-Fold</div>
            <div style="color:rgba(255,255,255,0.7);font-size:0.75rem;">Cross Validation</div>
        </div>
        <div style="text-align:center;background:rgba(255,255,255,0.15);border-radius:12px;padding:14px 20px;">
            <div style="color:white;font-size:1.4rem;font-weight:800;">3</div>
            <div style="color:rgba(255,255,255,0.7);font-size:0.75rem;">Kelas (Line)</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Classification Report Table --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">Classification Report</div>
        <div class="card-body" style="padding:0 0 18px;">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>KELAS</th>
                            <th>PRECISION</th>
                            <th>RECALL</th>
                            <th>F1-SCORE</th>
                            <th>SUPPORT</th>
                            <th>KUALITAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $row)
                        <tr>
                            <td>
                                <span class="badge {{ $row->line == 1 ? 'badge-green' : ($row->line == 2 ? 'badge-blue' : 'badge-yellow') }}">
                                    Line {{ $row->line }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="flex:1;background:#f0f0f0;border-radius:4px;height:6px;min-width:80px;">
                                        <div style="height:6px;border-radius:4px;background:#3b6d11;width:{{ $row->precision_val*100 }}%;"></div>
                                    </div>
                                    <span style="font-weight:600;font-size:0.85rem;">{{ number_format($row->precision_val, 4) }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="flex:1;background:#f0f0f0;border-radius:4px;height:6px;min-width:80px;">
                                        <div style="height:6px;border-radius:4px;background:#3b6d11;width:{{ $row->recall_val*100 }}%;"></div>
                                    </div>
                                    <span style="font-weight:600;font-size:0.85rem;">{{ number_format($row->recall_val, 4) }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:700;font-size:0.95rem;color:#3b6d11;">{{ number_format($row->f1_score, 4) }}</span>
                            </td>
                            <td>{{ number_format($row->support) }}</td>
                            <td>
                                @if($row->f1_score >= 0.90)
                                    <span class="badge badge-green">Sangat Baik</span>
                                @elseif($row->f1_score >= 0.80)
                                    <span class="badge badge-blue">Baik</span>
                                @else
                                    <span class="badge badge-orange">Cukup</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f0f7e8;">
                            <td colspan="2" style="font-weight:700;color:#1a2e0a;padding:12px 14px;">Overall Accuracy</td>
                            <td colspan="2" style="font-weight:800;font-size:1.1rem;color:#3b6d11;padding:12px 14px;">{{ $akurasi }}%</td>
                            <td style="padding:12px 14px;font-weight:600;">6.516</td>
                            <td style="padding:12px 14px;"><span class="badge badge-green">Sangat Baik</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Charts Row --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Feature Importance Chart --}}
    <div class="card">
        <div class="card-header">Feature Importance (Random Forest)</div>
        <div class="card-body">
            <div style="position:relative; height: 320px; width: 100%;">
                <canvas id="featureImportanceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- CV Scores Chart --}}
    <div class="card">
        <div class="card-header">Cross-Validation Scores (5-Fold)</div>
        <div class="card-body">
            <div style="position:relative; height: 320px; width: 100%;">
                <canvas id="cvChart"></canvas>
            </div>
            <div style="margin-top:16px;padding:12px;background:#f0f7e8;border-radius:10px;text-align:center;">
                <span style="font-size:0.84rem;color:#3b6d11;font-weight:600;">
                    Rata-rata CV: {{ number_format($cvMean*100, 2) }}% ± {{ number_format($cvStd*100, 2) }}%
                </span>
            </div>
        </div>
    </div>

</div>

{{-- Feature Importance Table --}}
<div class="card">
    <div class="card-header">Detail Feature Importance</div>
    <div class="card-body" style="padding:0 0 18px;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>RANK</th>
                        <th>FITUR</th>
                        <th>NAMA</th>
                        <th>IMPORTANCE SCORE</th>
                        <th>PROPORSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($featureImportance as $i => $fi)
                    <tr>
                        <td style="font-weight:700;color:{{ $i < 3 ? '#3b6d11' : '#6b7280' }};">#{{ $i+1 }}</td>
                        <td><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.82rem;">{{ $fi['fitur'] }}</code></td>
                        <td>{{ $fi['label'] }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="flex:1;background:#f0f0f0;border-radius:4px;height:8px;min-width:120px;">
                                    <div style="height:8px;border-radius:4px;background:#3b6d11;width:{{ $fi['importance']*100 }}%;"></div>
                                </div>
                                <span style="font-weight:700;min-width:50px;">{{ number_format($fi['importance']*100, 2) }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($i == 0)<span class="badge badge-green">Paling Penting</span>
                            @elseif($i <= 2)<span class="badge badge-blue">Penting</span>
                            @else<span class="badge" style="background:#f3f4f6;color:#6b7280;">Minor</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Feature Importance Chart (Horizontal Bar)
const fiCtx = document.getElementById('featureImportanceChart').getContext('2d');
const fiLabels = @json(array_column($featureImportance, 'label'));
const fiValues = @json(array_column($featureImportance, 'importance'));

new Chart(fiCtx, {
    type: 'bar',
    data: {
        labels: fiLabels,
        datasets: [{
            label: 'Feature Importance',
            data: fiValues,
            backgroundColor: [
                'rgba(59,109,17,0.85)',
                'rgba(99,153,34,0.85)',
                'rgba(74,137,21,0.85)',
                'rgba(86,107,27,0.85)',
                'rgba(42,80,12,0.85)',
                'rgba(119,153,59,0.85)',
                'rgba(156,163,175,0.85)',
            ],
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${(ctx.raw * 100).toFixed(2)}%`
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                max: 0.45,
                ticks: { callback: v => (v*100).toFixed(0)+'%' },
                grid: { color: '#f0f0f0' }
            },
            y: { grid: { display: false } }
        }
    }
});

// CV Scores Chart
const cvCtx = document.getElementById('cvChart').getContext('2d');
const cvScores = @json($cvScores);
new Chart(cvCtx, {
    type: 'bar',
    data: {
        labels: ['Fold 1', 'Fold 2', 'Fold 3', 'Fold 4', 'Fold 5'],
        datasets: [{
            label: 'Accuracy',
            data: cvScores,
            backgroundColor: 'rgba(59,109,17,0.75)',
            borderColor: '#3b6d11',
            borderWidth: 1.5,
            borderRadius: 8,
        }, {
            label: 'Mean',
            data: [{{ $cvMean }}, {{ $cvMean }}, {{ $cvMean }}, {{ $cvMean }}, {{ $cvMean }}],
            type: 'line',
            borderColor: '#ef4444',
            borderWidth: 2,
            borderDash: [5,5],
            pointRadius: 0,
            fill: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${(ctx.raw * 100).toFixed(2)}%`
                }
            }
        },
        scales: {
            y: {
                min: 0.88, max: 0.94,
                ticks: { callback: v => (v*100).toFixed(1)+'%' },
                grid: { color: '#f0f0f0' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>

@endsection
