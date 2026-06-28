@extends('layouts.app')

@section('title', 'Tambah Data Sensor')
@section('page-title', 'Tambah Data Sensor')
@section('page-subtitle', 'Input data sensor IoT baru')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;gap:8px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Form Tambah Data Sensor
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sensor.store') }}">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="waktu">Waktu <span style="color:#ef4444;">*</span></label>
                        <input type="datetime-local" id="waktu" name="waktu"
                               class="form-control {{ $errors->has('waktu') ? 'is-invalid' : '' }}"
                               value="{{ old('waktu') }}" required>
                        @error('waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="line">Line Irigasi <span style="color:#ef4444;">*</span></label>
                        <select id="line" name="line" class="form-control {{ $errors->has('line') ? 'is-invalid' : '' }}" required>
                            <option value="">-- Pilih Line --</option>
                            <option value="1" {{ old('line') == '1' ? 'selected' : '' }}>Line 1</option>
                            <option value="2" {{ old('line') == '2' ? 'selected' : '' }}>Line 2</option>
                            <option value="3" {{ old('line') == '3' ? 'selected' : '' }}>Line 3</option>
                        </select>
                        @error('line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_ec">Soil EC (μS/cm) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="soil_ec" name="soil_ec"
                               class="form-control {{ $errors->has('soil_ec') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_ec') }}" placeholder="contoh: 346" required>
                        @error('soil_ec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_humidity">Kelembaban Tanah (%) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="soil_humidity" name="soil_humidity"
                               class="form-control {{ $errors->has('soil_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_humidity') }}" placeholder="0 - 100" min="0" max="100" required>
                        @error('soil_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_temperature">Suhu Tanah (°C) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="soil_temperature" name="soil_temperature"
                               class="form-control {{ $errors->has('soil_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_temperature') }}" placeholder="contoh: 23.5" required>
                        @error('soil_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="co2">CO₂ (ppm) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="co2" name="co2"
                               class="form-control {{ $errors->has('co2') ? 'is-invalid' : '' }}"
                               value="{{ old('co2') }}" placeholder="contoh: 635.0" required>
                        @error('co2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="air_humidity">Kelembaban Udara (%) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="air_humidity" name="air_humidity"
                               class="form-control {{ $errors->has('air_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('air_humidity') }}" placeholder="0 - 100" min="0" max="100" required>
                        @error('air_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pressure">Tekanan Udara (hPa) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="pressure" name="pressure"
                               class="form-control {{ $errors->has('pressure') ? 'is-invalid' : '' }}"
                               value="{{ old('pressure') }}" placeholder="contoh: 1010.7" required>
                        @error('pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="air_temperature">Suhu Udara (°C) <span style="color:#ef4444;">*</span></label>
                        <input type="number" step="0.01" id="air_temperature" name="air_temperature"
                               class="form-control {{ $errors->has('air_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('air_temperature') }}" placeholder="contoh: 24.5" required>
                        @error('air_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Data
                    </button>
                    <a href="{{ route('sensor.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
