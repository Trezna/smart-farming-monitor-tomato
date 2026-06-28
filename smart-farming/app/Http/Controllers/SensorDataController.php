<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function index(Request $request)
    {
        $query = SensorData::query();

        // Filter by line
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }

        // Search by waktu
        if ($request->filled('search')) {
            $query->where('waktu', 'like', '%' . $request->search . '%');
        }

        $sensorData = $query->orderByDesc('waktu')->paginate(50)->withQueryString();

        return view('sensor.index', compact('sensorData'));
    }

    public function create()
    {
        return view('sensor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waktu'            => ['required', 'date'],
            'line'             => ['required', 'integer', 'in:1,2,3'],
            'soil_ec'          => ['required', 'numeric', 'min:0', 'max:9999'],
            'soil_humidity'    => ['required', 'numeric', 'min:0', 'max:100'],
            'soil_temperature' => ['required', 'numeric', 'min:-10', 'max:60'],
            'co2'              => ['required', 'numeric', 'min:0', 'max:9999'],
            'air_humidity'     => ['required', 'numeric', 'min:0', 'max:100'],
            'pressure'         => ['required', 'numeric', 'min:900', 'max:1100'],
            'air_temperature'  => ['required', 'numeric', 'min:-10', 'max:60'],
        ], [
            'waktu.required'            => 'Waktu wajib diisi.',
            'line.required'             => 'Line irigasi wajib dipilih.',
            'line.in'                   => 'Line harus 1, 2, atau 3.',
            'soil_ec.required'          => 'Soil EC wajib diisi.',
            'soil_ec.numeric'           => 'Soil EC harus berupa angka.',
            'soil_humidity.required'    => 'Kelembaban tanah wajib diisi.',
            'soil_humidity.max'         => 'Kelembaban tanah maksimal 100%.',
            'soil_temperature.required' => 'Suhu tanah wajib diisi.',
            'co2.required'              => 'CO2 wajib diisi.',
            'air_humidity.required'     => 'Kelembaban udara wajib diisi.',
            'pressure.required'         => 'Tekanan udara wajib diisi.',
            'air_temperature.required'  => 'Suhu udara wajib diisi.',
        ]);

        SensorData::create($validated);

        return redirect()->route('sensor.index')
            ->with('success', 'Data sensor berhasil ditambahkan.');
    }

    public function edit(SensorData $sensor)
    {
        return view('sensor.edit', compact('sensor'));
    }

    public function update(Request $request, SensorData $sensor)
    {
        $validated = $request->validate([
            'waktu'            => ['required', 'date'],
            'line'             => ['required', 'integer', 'in:1,2,3'],
            'soil_ec'          => ['required', 'numeric', 'min:0', 'max:9999'],
            'soil_humidity'    => ['required', 'numeric', 'min:0', 'max:100'],
            'soil_temperature' => ['required', 'numeric', 'min:-10', 'max:60'],
            'co2'              => ['required', 'numeric', 'min:0', 'max:9999'],
            'air_humidity'     => ['required', 'numeric', 'min:0', 'max:100'],
            'pressure'         => ['required', 'numeric', 'min:900', 'max:1100'],
            'air_temperature'  => ['required', 'numeric', 'min:-10', 'max:60'],
        ]);

        $sensor->update($validated);

        return redirect()->route('sensor.index')
            ->with('success', 'Data sensor berhasil diperbarui.');
    }

    public function destroy(SensorData $sensor)
    {
        $sensor->delete();
        return redirect()->route('sensor.index')
            ->with('success', 'Data sensor berhasil dihapus.');
    }
}
