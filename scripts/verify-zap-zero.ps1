# scripts/verify-zap-zero.ps1
# Automated verification that the application meets ZAP-zero acceptance criteria.
# Usage: powershell -File scripts/verify-zap-zero.ps1 [-BaseUrl http://127.0.0.1:8000]

param(
    [string]$BaseUrl = "http://127.0.0.1:8000"
)

$ErrorActionPreference = "Continue"
$fail = 0
$total = 0

function Check($desc, $pass) {
    $script:total++
    if ($pass) {
        Write-Host "  [PASS] $desc" -ForegroundColor Green
    } else {
        Write-Host "  [FAIL] $desc" -ForegroundColor Red
        $script:fail++
    }
}

Write-Host "==============================================="
Write-Host "  ZAP-Zero Verification Script"
Write-Host "  Target: $BaseUrl"
Write-Host "==============================================="
Write-Host ""

$endpoints = @("/", "/login", "/register", "/forgot-password", "/robots.txt", "/sitemap.xml")

foreach ($ep in $endpoints) {
    Write-Host "Checking ${ep}..."
    try {
        $resp = Invoke-WebRequest -Uri "$BaseUrl$ep" -UseBasicParsing -ErrorAction Stop
        $headers = $resp.Headers
        $body = $resp.Content

        # CSP
        $hasCsp = $headers.ContainsKey("Content-Security-Policy")
        Check "CSP header present on $ep" $hasCsp

        # No port 5173
        $no5173 = -not ($body -match "5173")
        Check "No port 5173 references on $ep" $no5173

        # No @vite/client
        $noVite = -not ($body -match "@vite/client")
        Check "No @vite/client on $ep" $noVite

        # No XSRF-TOKEN cookie
        $rawHeaders = $resp.RawContent
        $noXsrf = -not ($rawHeaders -match "XSRF-TOKEN")
        Check "No XSRF-TOKEN cookie on $ep" $noXsrf

        # No X-Powered-By
        $noPowered = -not ($headers.ContainsKey("X-Powered-By"))
        Check "No X-Powered-By on $ep" $noPowered

        # No debug leaks
        $noDebug = -not ($body -match "SQLSTATE|Stack trace|Whoops|vendor/laravel")
        Check "No debug info leak on $ep" $noDebug
    }
    catch {
        # For redirects (302), still check headers
        if ($_.Exception.Response) {
            $rawHeaders = $_.Exception.Response.Headers.ToString()
            $hasCsp = $rawHeaders -match "Content-Security-Policy"
            Check "CSP header present on $ep" $hasCsp
        } else {
            Check "Endpoint reachable: $ep" $false
        }
    }
    Write-Host ""
}

# Check stateless endpoints
Write-Host "Checking stateless routes..."
foreach ($ep in @("/robots.txt", "/sitemap.xml")) {
    try {
        $resp = Invoke-WebRequest -Uri "$BaseUrl$ep" -UseBasicParsing -ErrorAction Stop
        $raw = $resp.RawContent
        $noSession = -not ($raw -match "laravel_session")
        Check "No session cookie on $ep" $noSession
    }
    catch {
        Check "Stateless check reachable: $ep" $false
    }
}

Write-Host ""
Write-Host "==============================================="
if ($fail -eq 0) {
    Write-Host "  ALL $total CHECKS PASSED" -ForegroundColor Green
} else {
    Write-Host "  $fail / $total CHECKS FAILED" -ForegroundColor Red
}
Write-Host "==============================================="

exit $fail
