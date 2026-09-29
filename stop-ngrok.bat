@echo off
title Hentikan E-Modul Online
echo Menghentikan tunnel ngrok...
taskkill /F /IM ngrok.exe >nul 2>&1
echo Selesai! Tunnel online telah ditutup.
timeout /t 2 >nul
