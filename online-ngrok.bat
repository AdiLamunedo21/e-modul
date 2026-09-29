@echo off
title E-Modul Ngrok Online Tunnel
echo ===================================================================
echo               E-MODUL SMKN 3 YOGYAKARTA - NGROK ONLINE
echo ===================================================================
echo.
echo URL Akses Publik : https://bulldozer-reversion-fancied.ngrok-free.dev
echo Port Lokal       : 8000
echo.
echo Menjalankan server aplikasi dan tunnel internet...
echo [PERHATIAN] Biarkan jendela ini tetap terbuka agar web tetap online!
echo.
start /B php artisan serve --port=8000
C:\laragon\bin\ngrok\ngrok.exe http 8000 --url https://bulldozer-reversion-fancied.ngrok-free.dev
