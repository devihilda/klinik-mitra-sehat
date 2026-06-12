#!/usr/bin/env bash
# scripts/verify-zap-zero.sh
# Automated verification that the application meets ZAP-zero acceptance criteria.
# Usage: bash scripts/verify-zap-zero.sh [BASE_URL]
# Default BASE_URL: http://127.0.0.1:8000

set -euo pipefail

BASE="${1:-http://127.0.0.1:8000}"
FAIL=0
TOTAL=0

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

check() {
    TOTAL=$((TOTAL+1))
    local desc="$1"
    local result="$2"
    if [ "$result" = "PASS" ]; then
        echo -e "  ${GREEN}✓${NC} $desc"
    else
        echo -e "  ${RED}✗${NC} $desc"
        FAIL=$((FAIL+1))
    fi
}

echo "═══════════════════════════════════════════════"
echo "  ZAP-Zero Verification Script"
echo "  Target: $BASE"
echo "═══════════════════════════════════════════════"
echo ""

ENDPOINTS=(
    "/"
    "/login"
    "/register"
    "/forgot-password"
    "/robots.txt"
    "/sitemap.xml"
)

for EP in "${ENDPOINTS[@]}"; do
    echo "Checking ${EP}..."
    HEADERS=$(curl -sD - -o /tmp/zap_body.html "$BASE$EP" 2>/dev/null)
    BODY=$(cat /tmp/zap_body.html)

    # CSP header present
    if echo "$HEADERS" | grep -qi "Content-Security-Policy"; then
        check "CSP header present on $EP" "PASS"
    else
        check "CSP header present on $EP" "FAIL"
    fi

    # No port 5173
    if echo "$BODY" | grep -q "5173"; then
        check "No port 5173 references on $EP" "FAIL"
    else
        check "No port 5173 references on $EP" "PASS"
    fi

    # No @vite/client
    if echo "$BODY" | grep -q "@vite/client"; then
        check "No @vite/client on $EP" "FAIL"
    else
        check "No @vite/client on $EP" "PASS"
    fi

    # No XSRF-TOKEN cookie
    if echo "$HEADERS" | grep -qi "Set-Cookie.*XSRF-TOKEN"; then
        check "No XSRF-TOKEN cookie on $EP" "FAIL"
    else
        check "No XSRF-TOKEN cookie on $EP" "PASS"
    fi

    # No X-Powered-By
    if echo "$HEADERS" | grep -qi "X-Powered-By"; then
        check "No X-Powered-By on $EP" "FAIL"
    else
        check "No X-Powered-By on $EP" "PASS"
    fi

    # No stack traces
    if echo "$BODY" | grep -q "SQLSTATE\|Stack trace\|Whoops\|vendor/laravel"; then
        check "No debug info leak on $EP" "FAIL"
    else
        check "No debug info leak on $EP" "PASS"
    fi
    echo ""
done

# Check stateless endpoints don't set session cookies
echo "Checking stateless routes..."
for EP in "/robots.txt" "/sitemap.xml"; do
    HEADERS=$(curl -sD - -o /dev/null "$BASE$EP" 2>/dev/null)
    if echo "$HEADERS" | grep -qi "Set-Cookie.*laravel_session"; then
        check "No session cookie on $EP" "FAIL"
    else
        check "No session cookie on $EP" "PASS"
    fi
done

echo ""
echo "═══════════════════════════════════════════════"
if [ $FAIL -eq 0 ]; then
    echo -e "  ${GREEN}ALL $TOTAL CHECKS PASSED${NC}"
else
    echo -e "  ${RED}$FAIL / $TOTAL CHECKS FAILED${NC}"
fi
echo "═══════════════════════════════════════════════"

rm -f /tmp/zap_body.html
exit $FAIL
