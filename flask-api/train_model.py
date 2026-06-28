"""
Script training Random Forest untuk Smart Farming Tomat
Melatih model dari dataset_final_tomat_irigasi.csv
dan menyimpan model.pkl + scaler.pkl

Usage:
    python train_model.py
"""

import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report
import joblib
import os
import sys

# ==== KONFIGURASI ====
CSV_PATH = os.path.join(os.path.dirname(__file__), '..', 'dataset_final_tomat_irigasi.csv')
MODEL_OUTPUT = os.path.join(os.path.dirname(__file__), 'model.pkl')
SCALER_OUTPUT = os.path.join(os.path.dirname(__file__), 'scaler.pkl')

FEATURE_COLS = [
    'soil_ec', 'soil_humidity', 'soil_temperature',
    'co2', 'air_humidity', 'pressure', 'air_temperature'
]
TARGET_COL = 'line'

# ==== LOAD DATA ====
print(f"[INFO] Memuat dataset dari: {CSV_PATH}")
if not os.path.exists(CSV_PATH):
    print(f"[ERROR] File tidak ditemukan: {CSV_PATH}")
    sys.exit(1)

df = pd.read_csv(CSV_PATH)
print(f"[OK] Dataset dimuat: {df.shape[0]:,} baris x {df.shape[1]} kolom")

# ==== PERSIAPAN FITUR ====
X = df[FEATURE_COLS].values
y = df[TARGET_COL].values

print(f"\n[INFO] Distribusi label:")
for label in sorted(np.unique(y)):
    count = (y == label).sum()
    print(f"   Line {label}: {count:,} data ({count/len(y)*100:.1f}%)")

# ==== TRAIN/TEST SPLIT ====
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, stratify=y, random_state=42
)
print(f"\n[INFO] Train: {len(X_train):,} | Test: {len(X_test):,}")

# ==== SCALING ====
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

# ==== TRAINING ====
print("\n[INFO] Melatih Random Forest (200 trees, max_depth=10)...")
rf = RandomForestClassifier(
    n_estimators=200,
    max_depth=10,
    random_state=42,
    n_jobs=-1  # gunakan semua CPU cores
)
rf.fit(X_train_scaled, y_train)
print("[OK] Training selesai!")

# ==== EVALUASI ====
y_pred = rf.predict(X_test_scaled)
accuracy = accuracy_score(y_test, y_pred)

print(f"\n[INFO] Test Accuracy: {accuracy:.4f} ({accuracy*100:.2f}%)")
print("\n[INFO] Classification Report:")
print(classification_report(y_test, y_pred, digits=4))

print("\n[INFO] Feature Importance:")
importance_df = pd.DataFrame({
    'Fitur': FEATURE_COLS,
    'Importance': rf.feature_importances_
}).sort_values('Importance', ascending=False)
for _, row in importance_df.iterrows():
    bar = '#' * int(row['Importance'] * 50)
    print(f"   {row['Fitur']:20s}: {bar} {row['Importance']:.4f}")

# ==== SIMPAN MODEL ====
joblib.dump(rf, MODEL_OUTPUT)
joblib.dump(scaler, SCALER_OUTPUT)

print(f"\n[SAVED] Model disimpan ke: {MODEL_OUTPUT}")
print(f"[SAVED] Scaler disimpan ke: {SCALER_OUTPUT}")
print("\n[NEXT] Sekarang jalankan: python app.py")
