@extends('layouts.app')

@section('title', 'Edit Data Sensor')
@section('page-title', 'Edit Data Sensor')
@section('page-subtitle', 'Perbarui data sensor IoT #{{ $sensor->id }}')

@section('content')
<div style="max-width:700px;">
    <div class="card">
        <div class="card-header" style="display:flex;align-items:center;gap:8px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b6d11" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Data Sensor — ID #{{ $sensor->id }}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sensor.update', $sensor->id) }}">
                @csrf @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="waktu">Waktu <span style="color:#ef4444;">*</span></label>
                        <input type="datetime-local" id="waktu" name="waktu"
                               class="form-control {{ $errors->has('waktu') ? 'is-invalid' : '' }}"
                               value="{{ old('waktu', \Carbon\Carbon::parse($sensor->waktu)->format('Y-m-d\TH:i')) }}" required>
                        @error('waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label" for="line">Line Irigasi <span style="color:#ef4444;">*</span></label>
                        <select id="line" name="line" class="form-control {{ $errors->has('line') ? 'is-invalid' : '' }}" required>
                            <option value="1" {{ old('line', $sensor->line) == '1' ? 'selected' : '' }}>Line 1</option>
                            <option value="2" {{ old('line', $sensor->line) == '2' ? 'selected' : '' }}>Line 2</option>
                            <option value="3" {{ old('line', $sensor->line) == '3' ? 'selected' : '' }}>Line 3</option>
                        </select>
                        @error('line')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_ec">Soil EC (μS/cm)</label>
                        <input type="number" step="0.01" id="soil_ec" name="soil_ec"
                               class="form-control {{ $errors->has('soil_ec') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_ec', $sensor->soil_ec) }}" required>
                        @error('soil_ec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_humidity">Kelembaban Tanah (%)</label>
                        <input type="number" step="0.01" id="soil_humidity" name="soil_humidity"
                               class="form-control {{ $errors->has('soil_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_humidity', $sensor->soil_humidity) }}" min="0" max="100" required>
                        @error('soil_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="soil_temperature">Suhu Tanah (°C)</label>
                        <input type="number" step="0.01" id="soil_temperature" name="soil_temperature"
                               class="form-control {{ $errors->has('soil_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('soil_temperature', $sensor->soil_temperature) }}" required>
                        @error('soil_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="co2">CO₂ (ppm)</label>
                        <input type="number" step="0.01" id="co2" name="co2"
                               class="form-control {{ $errors->has('co2') ? 'is-invalid' : '' }}"
                               value="{{ old('co2', $sensor->co2) }}" required>
                        @error('co2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="air_humidity">Kelembaban Udara (%)</label>
                        <input type="number" step="0.01" id="air_humidity" name="air_humidity"
                               class="form-control {{ $errors->has('air_humidity') ? 'is-invalid' : '' }}"
                               value="{{ old('air_humidity', $sensor->air_humidity) }}" min="0" max="100" required>
                        @error('air_humidity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pressure">Tekanan Udara (hPa)</label>
                        <input type="number" step="0.01" id="pressure" name="pressure"
                               class="form-control {{ $errors->has('pressure') ? 'is-invalid' : '' }}"
                               value="{{ old('pressure', $sensor->pressure) }}" required>
                        @error('pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="air_temperature">Suhu Udara (°C)</label>
                        <input type="number" step="0.01" id="air_temperature" name="air_temperature"
                               class="form-control {{ $errors->has('air_temperature') ? 'is-invalid' : '' }}"
                               value="{{ old('air_temperature', $sensor->air_temperature) }}" required>
                        @error('air_temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Update Data
                    </button>
                    <a href="{{ route('sensor.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
