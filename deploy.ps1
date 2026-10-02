# ==============================================================================
# Script Otomatis Deployment E-Modul SKAGATA ke VPS
# ==============================================================================

param (
    [switch]$SkipBuild,
    [switch]$SkipGitPush
)

$ErrorActionPreference = "Stop"

$SSH_USER = "skagataa"
$SSH_HOST = "46.250.227.158"
$SSH_KEY  = "$env:USERPROFILE\.ssh\id_ed25519"
$REMOTE_DIR = "/home/skagataa/e-modul"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host " 🚀 MEMULAI PROSES DEPLOYMENT E-MODUL SKAGATA KE SERVER" -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

# 1. Build Frontend Assets
if (-not $SkipBuild) {
    Write-Host "`n📦 [1/4] Membangun Frontend Assets (Vite)..." -ForegroundColor Yellow
    npm.cmd run build
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Gagal membangun frontend assets (Vite)."
    }
} else {
    Write-Host "`n⏩ [1/4] Melewati build frontend (SkipBuild aktif)." -ForegroundColor Gray
}

# 2. Git Push (jika ada commit yang belum di-push)
if (-not $SkipGitPush) {
    Write-Host "`n📤 [2/4] Memeriksa status Git lokal..." -ForegroundColor Yellow
    $gitStatus = git status --porcelain
    if ($gitStatus) {
        Write-Host "⚠️ Ada perubahan lokal yang belum di-commit. Disarankan commit terlebih dahulu." -ForegroundColor Yellow
    }
    Write-Host "Melakukan push ke GitHub (master)..." -ForegroundColor Yellow
    git push origin master
}

# 3. Server: Git Pull, Composer & Migration
Write-Host "`n🔄 [3/4] Menjalankan update di server VPS..." -ForegroundColor Yellow
$remoteCommands = @(
    "cd $REMOTE_DIR",
    "git pull origin master",
    "composer install --no-dev --optimize-autoloader",
    "php artisan migrate --force",
    "php artisan storage:link",
    "php artisan optimize"
) -join " && "

ssh -o StrictHostKeyChecking=no -i "$SSH_KEY" "$SSH_USER@$SSH_HOST" "$remoteCommands"

# 4. Upload Built Assets
Write-Host "`n☁️ [4/4] Mengunggah compiled assets (public/build) ke server..." -ForegroundColor Yellow
scp -r -o StrictHostKeyChecking=no -i "$SSH_KEY" "public/build" "$SSH_USER@$SSH_HOST`:$REMOTE_DIR/public/"

Write-Host "`n==========================================================" -ForegroundColor Green
Write-Host " ✨ DEPLOYMENT SELESAI DENGAN SUKSES! ✨" -ForegroundColor Green
Write-Host " 🌐 Website: https://skagataaa.my.id" -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Green
