#!/usr/bin/env bash
# scripts/start-security-scan.sh
# Prepares the application for a production-like OWASP ZAP security scan.
# Usage: bash scripts/start-security-scan.sh

set -euo pipefail
cd "$(dirname "$0")/.."

# Create reports directory if it doesn't exist
mkdir -p reports

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}[1/7] Stopping Vite dev server if running...${NC}"
pkill -f "vite" 2>/dev/null || true

echo -e "${YELLOW}[2/7] Removing public/hot file...${NC}"
rm -f public/hot

echo -e "${YELLOW}[3/7] Building production assets with SRI...${NC}"
npm run build

echo -e "${YELLOW}[4/7] Verifying build manifest has integrity hashes...${NC}"
if ! grep -q '"integrity"' public/build/manifest.json; then
    echo -e "${RED}FAIL: manifest.json missing integrity hashes. Check vite-plugin-manifest-sri.${NC}"
    exit 1
fi

echo -e "${YELLOW}[5/7] Setting production environment...${NC}"
export APP_DEBUG=false
export APP_ENV=production

echo -e "${YELLOW}[6/7] Clearing Laravel caches...${NC}"
php artisan optimize:clear

echo -e "${YELLOW}[7/7] Verifying HTML does not contain port 5173...${NC}"
php artisan serve --port=8000 &
SERVER_PID=$!
sleep 3

HTML=$(curl -s http://127.0.0.1:8000/)
kill $SERVER_PID 2>/dev/null || true

if echo "$HTML" | grep -q "5173"; then
    echo -e "${RED}FAIL: HTML still contains references to port 5173!${NC}"
    echo "$HTML" | grep "5173"
    exit 1
fi

if echo "$HTML" | grep -q "@vite/client"; then
    echo -e "${RED}FAIL: HTML contains @vite/client reference!${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Security scan environment is ready!   ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Start the server with:"
echo "  APP_DEBUG=false APP_ENV=production php artisan serve"
echo ""
echo "Then run OWASP ZAP against http://127.0.0.1:8000"
echo "Use the ZAP config at: scripts/zap-scan-policy.yaml"
