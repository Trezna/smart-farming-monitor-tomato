@extends('layouts.app')

@section('title', 'Data Sensor')
@section('page-title', 'Data Sensor')
@section('page-subtitle', 'Kelola data sensor IoT irigasi tomat')

@section('content')

{{-- Filter & Actions --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="GET" action="{{ route('sensor.index') }}" id="filterForm">
            <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Filter Line</label>
                    <select name="line" class="form-control" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Line</option>
                        <option value="1" {{ request('line') == '1' ? 'selected' : '' }}>Line 1</option>
                        <option value="2" {{ request('line') == '2' ? 'selected' : '' }}>Line 2</option>
                        <option value="3" {{ request('line') == '3' ? 'selected' : '' }}>Line 3</option>
                    </select>
                </div>
                <div style="flex:2;min-width:200px;">
                    <label class="form-label">Cari Waktu</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="contoh: 2023-06-29"
                               class="form-control" style="padding-left:34px;">
                    </div>
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                    <a href="{{ route('sensor.index') }}" class="btn btn-secondary">Reset</a>
                </div>

                @if(auth()->user()->isAdmin())
                <div style="margin-left:auto;">
                    <a href="{{ route('sensor.create') }}" class="btn btn-primary">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Data
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <span>Data Sensor ({{ number_format($sensorData->total()) }} total)</span>
        <span style="font-size:0.78rem;color:#6b7280;font-weight:400;">
            Halaman {{ $sensorData->currentPage() }} dari {{ $sensorData->lastPage() }}
        </span>
    </div>
    <div class="card-body" style="padding:0 0 18px;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>WAKTU</th>
                        <th>LINE</th>
                        <th>SOIL EC</th>
                        <th>KELEMBABAN TANAH</th>
                        <th>SUHU TANAH</th>
                        <th>CO₂</th>
                        <th>KELEMBABAN UDARA</th>
                        <th>TEKANAN</th>
                        <th>SUHU UDARA</th>
                        @if(auth()->user()->isAdmin())
                        <th>AKSI</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($sensorData as $i => $data)
                    <tr>
                        <td style="color:#9ca3af;font-size:0.78rem;">{{ ($sensorData->currentPage()-1)*50 + $i + 1 }}</td>
                        <td style="white-space:nowrap;font-size:0.82rem;">{{ \Carbon\Carbon::parse($data->waktu)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <span class="badge {{ $data->line == 1 ? 'badge-green' : ($data->line == 2 ? 'badge-blue' : 'badge-yellow') }}">
                                Line {{ $data->line }}
                            </span>
                        </td>
                        <td>{{ $data->soil_ec }}</td>
                        <td>{{ $data->soil_humidity }}%</td>
                        <td>{{ $data->soil_temperature }}°C</td>
                        <td>{{ $data->co2 }}</td>
                        <td>{{ $data->air_humidity }}%</td>
                        <td>{{ $data->pressure }}</td>
                        <td>{{ $data->air_temperature }}°C</td>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('sensor.edit', $data->id) }}" class="btn btn-secondary btn-sm">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('sensor.destroy', $data->id) }}"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? '11' : '10' }}" style="text-align:center;padding:40px;color:#9ca3af;">
                            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="1.5" style="margin:0 auto 8px;display:block;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sensorData->hasPages())
        <div style="padding:16px 22px;border-top:1px solid #f0f0f0;display:flex;align-items:center;justify-content:between;flex-wrap:wrap;gap:10px;">
            <span style="font-size:0.82rem;color:#6b7280;">
                Menampilkan {{ $sensorData->firstItem() }}–{{ $sensorData->lastItem() }} dari {{ number_format($sensorData->total()) }} data
            </span>
            <div class="pagination" style="margin-left:auto;">
                @if($sensorData->onFirstPage())
                    <span class="page-link" style="opacity:0.4;cursor:not-allowed;">← Prev</span>
                @else
                    <a class="page-link" href="{{ $sensorData->previousPageUrl() }}">← Prev</a>
                @endif

                @foreach($sensorData->getUrlRange(max(1,$sensorData->currentPage()-2), min($sensorData->lastPage(),$sensorData->currentPage()+2)) as $page => $url)
                    <a class="page-link {{ $page == $sensorData->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                @endforeach

                @if($sensorData->hasMorePages())
                    <a class="page-link" href="{{ $sensorData->nextPageUrl() }}">Next →</a>
                @else
                    <span class="page-link" style="opacity:0.4;cursor:not-allowed;">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
