<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PrediksiController extends Controller
{
    public function index()
    {
        return view('prediksi.index');
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'soil_ec'          => ['required', 'numeric', 'min:0', 'max:9999'],
            'soil_humidity'    => ['required', 'numeric', 'min:0', 'max:100'],
            'soil_temperature' => ['required', 'numeric', 'min:-10', 'max:60'],
            'co2'              => ['required', 'numeric', 'min:0', 'max:9999'],
            'air_humidity'     => ['required', 'numeric', 'min:0', 'max:100'],
            'pressure'         => ['required', 'numeric', 'min:900', 'max:1100'],
            'air_temperature'  => ['required', 'numeric', 'min:-10', 'max:60'],
        ], [
            'soil_ec.required'          => 'EC Tanah wajib diisi.',
            'soil_ec.numeric'           => 'EC Tanah harus angka.',
            'soil_humidity.required'    => 'Kelembaban tanah wajib diisi.',
            'soil_humidity.max'         => 'Kelembaban tanah maksimal 100%.',
            'soil_temperature.required' => 'Suhu tanah wajib diisi.',
            'co2.required'              => 'CO2 wajib diisi.',
            'air_humidity.required'     => 'Kelembaban udara wajib diisi.',
            'pressure.required'         => 'Tekanan udara wajib diisi.',
            'air_temperature.required'  => 'Suhu udara wajib diisi.',
        ]);

        try {
            $flaskUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');
            $client   = new Client(['timeout' => 10]);

            $response = $client->post("{$flaskUrl}/predict", [
                'json' => [
                    'soil_ec'          => (float) $validated['soil_ec'],
                    'soil_humidity'    => (float) $validated['soil_humidity'],
                    'soil_temperature' => (float) $validated['soil_temperature'],
                    'co2'              => (float) $validated['co2'],
                    'air_humidity'     => (float) $validated['air_humidity'],
                    'pressure'         => (float) $validated['pressure'],
                    'air_temperature'  => (float) $validated['air_temperature'],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return view('prediksi.index', [
                'result'    => $result,
                'inputData' => $validated,
            ]);

        } catch (RequestException $e) {
            return view('prediksi.index', [
                'error'     => 'Gagal terhubung ke Flask API. Pastikan Flask server berjalan di port 5000.',
                'inputData' => $validated,
            ]);
        } catch (\Exception $e) {
            return view('prediksi.index', [
                'error'     => 'Terjadi kesalahan: ' . $e->getMessage(),
                'inputData' => $validated,
            ]);
        }
    }
}
