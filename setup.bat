@echo off
title Smart Farming - Setup & Start
color 0A

echo ============================================================
echo   SMART FARMING TOMAT - Auto Setup ^& Start
echo   Laravel + Flask API + MySQL (XAMPP)
echo ============================================================
echo.

REM ============================================================
REM [STEP 1] Cek dan Start MySQL (XAMPP812)
REM ============================================================
echo [1/5] Memeriksa status MySQL...

set MYSQL_BIN=C:\xampp812\mysql\bin
set MYSQL_EXE=%MYSQL_BIN%\mysql.exe
set MYSQLD_EXE=%MYSQL_BIN%\mysqld.exe

REM Cek apakah mysqld sudah berjalan
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I "mysqld.exe" >NUL
if %ERRORLEVEL% EQU 0 (
    echo [OK] MySQL sudah berjalan.
    goto :step2
)

echo [INFO] MySQL belum berjalan. Mencoba start...

REM Coba via service dulu (jika ada)
net start mysql 2>NUL
net start mysql80 2>NUL
net start xampp-mysql 2>NUL

REM Tunggu sebentar
timeout /t 2 /nobreak >NUL

REM Cek lagi
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I "mysqld.exe" >NUL
if %ERRORLEVEL% EQU 0 (
    echo [OK] MySQL berhasil distart via service.
    goto :step2
)

REM Jika service gagal, start mysqld langsung (background)
echo [INFO] Mencoba start MySQL langsung dari XAMPP812...
if exist "%MYSQLD_EXE%" (
    start /B "" "%MYSQLD_EXE%" --defaults-file="C:\xampp812\mysql\bin\my.ini" --standalone
    echo [INFO] Menunggu MySQL ready (5 detik)...
    timeout /t 5 /nobreak >NUL
) else (
    echo [ERROR] MySQL tidak ditemukan di C:\xampp812\mysql\bin\
    echo         Buka XAMPP Control Panel dan start MySQL secara manual.
    echo         Lalu jalankan setup.bat ini kembali.
    pause
    exit /b 1
)

REM Cek final
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I "mysqld.exe" >NUL
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] MySQL gagal distart otomatis.
    echo.
    echo SOLUSI MANUAL:
    echo   1. Buka XAMPP Control Panel (C:\xampp812\xampp-control.exe)
    echo   2. Klik START pada baris MySQL
    echo   3. Jalankan setup.bat ini lagi
    pause
    exit /b 1
)
echo [OK] MySQL berhasil distart.

:step2
REM ============================================================
REM [STEP 2] Buat Database
REM ============================================================
echo.
echo [2/5] Membuat database smart_farming_db...

"%MYSQL_EXE%" -u root -e "CREATE DATABASE IF NOT EXISTS smart_farming_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>NUL
if %ERRORLEVEL% NEQ 0 (
    REM Coba dengan password kosong eksplisit
    "%MYSQL_EXE%" -u root --password="" -e "CREATE DATABASE IF NOT EXISTS smart_farming_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>NUL
)
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Gagal membuat database!
    echo         Pastikan MySQL berjalan dan user root tidak berpassword.
    pause
    exit /b 1
)
echo [OK] Database smart_farming_db siap.

REM ============================================================
REM [STEP 3] Migrate + Seed Laravel
REM ============================================================
echo.
echo [3/5] Menjalankan migrasi dan seeder Laravel...
cd /d "D:\FrameworkUAS\smart-farming"

php artisan migrate:fresh --seed --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Migrate atau Seed gagal!
    pause
    exit /b 1
)
echo [OK] Database sudah terisi data.

REM ============================================================
REM [STEP 4] Start Flask API (terminal baru)
REM ============================================================
echo.
echo [4/5] Menjalankan Flask API di terminal baru...
start "Flask API - Smart Farming" cmd /k "cd /d D:\FrameworkUAS\flask-api && echo Menjalankan Flask API... && python app.py"
timeout /t 2 /nobreak >NUL
echo [OK] Flask API berjalan di http://127.0.0.1:5000

REM ============================================================
REM [STEP 5] Start Laravel (terminal baru)
REM ============================================================
echo.
echo [5/5] Menjalankan Laravel di terminal baru...
start "Laravel - Smart Farming" cmd /k "cd /d D:\FrameworkUAS\smart-farming && echo Menjalankan Laravel... && php artisan serve --port=8000"
timeout /t 2 /nobreak >NUL
echo [OK] Laravel berjalan di http://127.0.0.1:8000

REM ============================================================
REM SELESAI
REM ============================================================
echo.
echo ============================================================
echo   SEMUA SERVICES SUDAH BERJALAN!
echo ============================================================
echo.
echo   Laravel   : http://127.0.0.1:8000
echo   Flask API : http://127.0.0.1:5000
echo.
echo   Login Admin  : admin@smartfarming.com  / admin123
echo   Login Viewer : viewer@smartfarming.com / viewer123
echo.
echo   Tekan tombol apapun untuk membuka browser...
pause >NUL
start "" "http://127.0.0.1:8000"
