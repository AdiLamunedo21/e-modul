@echo off
cd /d "%~dp0"
title E-Modul SMKN 3 Yogyakarta - Ngrok Online

echo ===================================================================
echo             E-MODUL SMKN 3 YOGYAKARTA - NGROK ONLINE
echo ===================================================================
echo.
echo URL Akses Publik : https://bulldozer-reversion-fancied.ngrok-free.dev
echo Port Lokal       : 8000
echo.
echo [1/3] Menyiapkan port 8000...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":8000" ^| findstr "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)

echo [2/3] Menjalankan server aplikasi Laravel (Port 8000)...
start /B php artisan serve --port=8000 >nul 2>&1

echo [3/3] Menghubungkan tunnel Ngrok...
echo.
echo ===================================================================
echo   STATUS: ONLINE!
echo   Buka URL di HP / Laptop:
echo   https://bulldozer-reversion-fancied.ngrok-free.dev
echo.
echo   [PERHATIAN] Biarkan jendela ini tetap terbuka agar web tetap online!
echo   Tekan Ctrl + C atau tutup jendela ini untuk berhenti online.
echo ===================================================================
echo.

C:\laragon\bin\ngrok\ngrok.exe http 8000 --url https://bulldozer-reversion-fancied.ngrok-free.dev

for /f "tokens=5" %%a in ('netstat -aon ^| findstr ":8000" ^| findstr "LISTENING"') do (
    taskkill /F /PID %%a >nul 2>&1
)

echo.
echo Server telah dinonaktifkan.
pause

