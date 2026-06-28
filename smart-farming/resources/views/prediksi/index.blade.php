@extends('layouts.app')

@section('title', 'Prediksi Irigasi')
@section('page-title', 'Prediksi Line Irigasi')
@section('page-subtitle', 'Input nilai sensor → prediksi line irigasi menggunakan Random Forest')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Form Prediksi --}}
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;gap:8px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Input Data Sensor
        </div>
        <div class="card-body">
            @if(isset($error))
            <div class="alert alert-danger">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $error }}
            </div>
            @endif

            <form method="POST" action="{{ route('prediksi.predict') }}" id="prediksiForm">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

                    <div class="form-group">
                    <label class="form-label" for="soil_ec">
                        Soil EC (μS/cm)
                        <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="number" step="0.01" id="soil_ec" name="soil_ec"
                           class="form-control {{ $errors->has('soil_ec') ? 'is-invalid' : '' }}"
                           value="{{ old('soil_ec', $inputData['soil_ec'] ?? '') }}"
                           placeholder="0 - 9999" required>
                    @error('soil_ec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_humidity">
                            Kelembaban Tanah (%)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="soil_humidity" name="soil_humidity"
                               class="form-control {{ $errors->has('soil_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_humidity', $inputData['soil_humidity'] ?? '') }}"
                               placeholder="0 - 100" min="0" max="100" required>
                        @error('soil_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_temperature">
                            Suhu Tanah (°C)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="soil_temperature" name="soil_temperature"
                               class="form-control {{ $errors->has('soil_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_temperature', $inputData['soil_temperature'] ?? '') }}"
                               placeholder="-10 s/d 60" required>
                        @error('soil_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="co2">
                            CO₂ (ppm)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="co2" name="co2"
                               class="form-control {{ $errors->has('co2') ? 'is-invalid' : '' }}"
                               value="{{ old('co2', $inputData['co2'] ?? '') }}"
                               placeholder="0 - 9999" required>
                        @error('co2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="air_humidity">
                            Kelembaban Udara (%)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="air_humidity" name="air_humidity"
                               class="form-control {{ $errors->has('air_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('air_humidity', $inputData['air_humidity'] ?? '') }}"
                               placeholder="0 - 100" min="0" max="100" required>
                        @error('air_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pressure">
                            Tekanan Udara (hPa)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="pressure" name="pressure"
                               class="form-control {{ $errors->has('pressure') ? 'is-invalid' : '' }}"
                               value="{{ old('pressure', $inputData['pressure'] ?? '') }}"
                               placeholder="900 - 1100" required>
                        @error('pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="air_temperature">
                            Suhu Udara (°C)
                            <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="number" step="0.01" id="air_temperature" name="air_temperature"
                               class="form-control {{ $errors->has('air_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('air_temperature', $inputData['air_temperature'] ?? '') }}"
                               placeholder="-10 s/d 60" required>
                        @error('air_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="predictBtn" style="flex:1;justify-content:center;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Prediksi Sekarang
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="fillSample()">
                        Isi Contoh
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hasil Prediksi --}}
    <div>
        @if(isset($result))
        @php
            $lineNum = $result['prediction'];
            $probs   = $result['probabilities'] ?? [];
            $lineColors = [
                1 => ['bg'=>'#d1fae5','text'=>'#1a5e0a','border'=>'#3b6d11'],
                2 => ['bg'=>'#dbeafe','text'=>'#1e40af','border'=>'#3b82f6'],
                3 => ['bg'=>'#fefce8','text'=>'#854d0e','border'=>'#d97706'],
            ];
            $lc = $lineColors[$lineNum] ?? $lineColors[1];
        @endphp

        <div class="card" style="border:2px solid {{ $lc['border'] }};margin-bottom:16px;">
            <div class="card-body" style="text-align:center;padding:32px 24px;">
                <div style="font-size:0.84rem;color:#6b7280;font-weight:500;margin-bottom:8px;">Hasil Prediksi</div>
                <div style="font-size:3.5rem;font-weight:900;color:#1a5e0a;line-height:1;">
                    LINE {{ $lineNum }}
                </div>
                <div style="font-size:0.9rem;color:#3b6d11;opacity:0.85;margin-top:6px;font-weight:500;">
                    Irigasi Line {{ $lineNum }} direkomendasikan
                </div>

                @if(!empty($probs))
                <div style="margin-top:24px;text-align:left;">
                    <p style="font-size:0.82rem;font-weight:700;color:#374151;margin-bottom:10px;">Probabilitas per Kelas:</p>
                    @foreach($probs as $i => $prob)
                    @php $lineLabel = $i + 1; @endphp
                    <div style="margin-bottom:8px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                            <span style="font-size:0.8rem;color:#374151;font-weight:500;">Line {{ $lineLabel }}</span>
                            <span style="font-size:0.8rem;font-weight:700;color:{{ $lineLabel == $lineNum ? '#3b6d11' : '#6b7280' }};">{{ number_format($prob * 100, 1) }}%</span>
                        </div>
                        <div style="background:#f0f0f0;border-radius:4px;height:8px;">
                            <div style="height:8px;border-radius:4px;background:{{ $lineLabel == $lineNum ? '#3b6d11' : '#d1d5db' }};width:{{ $prob * 100 }}%;--target-width:{{ $prob * 100 }}%;animation:fillBar 1s ease-out forwards;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @else

        <div class="card">
            <div class="card-body" style="text-align:center;padding:40px 24px;">
                <div style="margin-bottom:12px;">
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="1.5" style="margin:0 auto;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p style="color:#6b7280;font-weight:500;font-size:0.9rem;">Isi form di sebelah kiri lalu klik</p>
                <p style="color:#3b6d11;font-weight:700;font-size:0.9rem;">"Prediksi Sekarang"</p>
                <div style="margin-top:20px;padding:14px;background:#f0f7e8;border-radius:10px;text-align:left;">
                    <p style="font-size:0.8rem;font-weight:700;color:#3b6d11;margin-bottom:8px;">Tentang Model</p>
                    <p style="font-size:0.78rem;color:#4a5568;line-height:1.6;">
                        Model Random Forest dilatih menggunakan 7 fitur sensor:<br>
                        soil_ec, soil_humidity, soil_temperature, co2, air_humidity, pressure, air_temperature
                        <br><br>
                        <strong>Akurasi: 90.84%</strong> pada test set (6.516 sampel)
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Info Fitur Penting --}}
        <div class="card" style="margin-top:16px;">
            <div class="card-header">Fitur Terpenting</div>
            <div class="card-body">
                @php
                $topFeatures = [
                    ['label'=>'Kelembaban Tanah','pct'=>40.99,'color'=>'#3b6d11'],
                    ['label'=>'EC Tanah','pct'=>30.27,'color'=>'#639922'],
                    ['label'=>'Suhu Tanah','pct'=>13.55,'color'=>'#4a8915'],
                ];
                @endphp
                @foreach($topFeatures as $f)
                <div style="margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                        <span style="font-size:0.82rem;font-weight:600;color:#374151;">{{ $f['label'] }}</span>
                        <span style="font-size:0.82rem;font-weight:700;color:{{ $f['color'] }};">{{ $f['pct'] }}%</span>
                    </div>
                    <div style="background:#f0f0f0;border-radius:4px;height:7px;">
                        <div style="height:7px;border-radius:4px;background:{{ $f['color'] }};width:{{ $f['pct'] }}%;--target-width:{{ $f['pct'] }}%;animation:fillBar 1s ease-out forwards;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('prediksiForm').addEventListener('submit', function() {
    const btn = document.getElementById('predictBtn');
    btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Memproses...';
    btn.disabled = true;
});

function fillSample() {
    document.getElementById('soil_ec').value = '346';
    document.getElementById('soil_humidity').value = '28.45';
    document.getElementById('soil_temperature').value = '23.5';
    document.getElementById('co2').value = '635.0';
    document.getElementById('air_humidity').value = '61.0';
    document.getElementById('pressure').value = '1010.7';
    document.getElementById('air_temperature').value = '24.5';
}

// Spin animation & Bar animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes fillBar { from { width: 0%; } to { width: var(--target-width); } }
`;
document.head.appendChild(style);
</script>

@endsection
