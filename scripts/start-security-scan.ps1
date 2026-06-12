# scripts/start-security-scan.ps1
# Prepares the application for a production-like OWASP ZAP security scan on Windows.
# Usage: powershell -File scripts/start-security-scan.ps1

$ErrorActionPreference = "Stop"
Set-Location (Split-Path $PSScriptRoot -Parent)

# Create reports directory if it doesn't exist
if (-not (Test-Path "reports")) {
    New-Item -Path "reports" -ItemType Directory -Force | Out-Null
    Write-Host "Created reports directory." -ForegroundColor Yellow
}

Write-Host "[1/7] Stopping Vite dev server if running..." -ForegroundColor Yellow
Get-Process -Name "node" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*vite*" } | Stop-Process -Force -ErrorAction SilentlyContinue

Write-Host "[2/7] Removing public/hot file..." -ForegroundColor Yellow
Remove-Item -Path "public/hot" -Force -ErrorAction SilentlyContinue

Write-Host "[3/7] Building production assets with SRI..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "FAIL: npm run build failed." -ForegroundColor Red; exit 1 }

Write-Host "[4/7] Verifying build manifest has integrity hashes..." -ForegroundColor Yellow
$manifest = Get-Content "public/build/manifest.json" -Raw
if ($manifest -notmatch '"integrity"') {
    Write-Host "FAIL: manifest.json missing integrity hashes." -ForegroundColor Red
    exit 1
}

Write-Host "[5/7] Setting production environment..." -ForegroundColor Yellow
$env:APP_DEBUG = "false"
$env:APP_ENV = "production"

Write-Host "[6/7] Clearing Laravel caches..." -ForegroundColor Yellow
php artisan optimize:clear

Write-Host "[7/7] Verifying public/hot does not exist..." -ForegroundColor Yellow
if (Test-Path "public/hot") {
    Write-Host "FAIL: public/hot still exists!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Security scan environment is ready!   " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Start the server with:"
Write-Host '  $env:APP_DEBUG="false"; $env:APP_ENV="production"; php artisan serve'
Write-Host ""
Write-Host "Then run OWASP ZAP against http://127.0.0.1:8000"
Write-Host "Use the ZAP config at: scripts/zap-scan-policy.yaml"
