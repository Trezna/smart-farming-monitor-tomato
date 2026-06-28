"""
Smart Farming Tomat — Flask API
Endpoint prediksi Random Forest untuk line irigasi tomat

Usage:
    python app.py

Requires: model.pkl (jalankan train_model.py terlebih dahulu)
"""

from flask import Flask, request, jsonify
import joblib
import numpy as np
import os

app = Flask(__name__)

# Load model
MODEL_PATH = os.path.join(os.path.dirname(__file__), 'model.pkl')
SCALER_PATH = os.path.join(os.path.dirname(__file__), 'scaler.pkl')

try:
    model = joblib.load(MODEL_PATH)
    scaler = joblib.load(SCALER_PATH)
    print("Model dan scaler berhasil dimuat.")
except FileNotFoundError as e:
    print(f"Error: {e}")
    print("   Jalankan 'python train_model.py' terlebih dahulu untuk membuat model.pkl")
    model = None
    scaler = None

FEATURE_COLS = [
    'soil_ec', 'soil_humidity', 'soil_temperature',
    'co2', 'air_humidity', 'pressure', 'air_temperature'
]


@app.route('/')
def index():
    return jsonify({
        'service': 'Smart Farming Tomat — Prediksi API',
        'status': 'running' if model else 'model not loaded',
        'endpoints': {
            'POST /predict': 'Prediksi line irigasi berdasarkan data sensor'
        },
        'features': FEATURE_COLS
    })


@app.route('/predict', methods=['POST'])
def predict():
    if model is None or scaler is None:
        return jsonify({
            'error': 'Model belum dimuat. Jalankan train_model.py terlebih dahulu.'
        }), 503

    data = request.get_json()
    if not data:
        return jsonify({'error': 'Request body harus berupa JSON'}), 400

    # Validasi semua fitur ada
    missing = [f for f in FEATURE_COLS if f not in data]
    if missing:
        return jsonify({
            'error': f'Fitur tidak lengkap: {missing}'
        }), 400

    try:
        # Susun fitur dalam urutan yang benar
        features = np.array([[
            float(data['soil_ec']),
            float(data['soil_humidity']),
            float(data['soil_temperature']),
            float(data['co2']),
            float(data['air_humidity']),
            float(data['pressure']),
            float(data['air_temperature']),
        ]])

        # Scale features
        features_scaled = scaler.transform(features)

        # Prediksi
        prediction = model.predict(features_scaled)[0]
        probabilities = model.predict_proba(features_scaled)[0].tolist()

        return jsonify({
            'prediction': int(prediction),
            'label': f'Line {int(prediction)}',
            'probabilities': [round(p, 4) for p in probabilities],
            'confidence': round(max(probabilities) * 100, 2),
            'status': 'success'
        })

    except ValueError as e:
        return jsonify({'error': f'Nilai tidak valid: {str(e)}'}), 400
    except Exception as e:
        return jsonify({'error': f'Terjadi kesalahan: {str(e)}'}), 500


@app.route('/health')
def health():
    return jsonify({
        'status': 'ok',
        'model_loaded': model is not None,
        'scaler_loaded': scaler is not None
    })


if __name__ == '__main__':
    print("Flask API Smart Farming Tomat berjalan di http://127.0.0.1:5000")
    app.run(host='0.0.0.0', port=5000, debug=True)
