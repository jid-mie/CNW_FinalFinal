#!/usr/bin/env bash

set -euo pipefail

BASE_URL="${BASE_URL:-http://127.0.0.1:8000}"
EMAIL="${EMAIL:-customer@example.com}"
PASSWORD="${PASSWORD:-your-password}"
BOOKING_ID="${BOOKING_ID:-123}"
AMOUNT="${AMOUNT:-100000}"
NOTE="${NOTE:-Test thanh toan QR}"
REFERENCE_CODE="${REFERENCE_CODE:-TEST-REF-001}"
SKIP_WEBHOOK="${SKIP_WEBHOOK:-0}"

echo "=== Step 1: Login and get access token ==="
LOGIN_RESPONSE="$(curl -sS -X POST "$BASE_URL/api/login" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")"

echo "$LOGIN_RESPONSE"

ACCESS_TOKEN="$(printf '%s' "$LOGIN_RESPONSE" | php -r '
$payload = json_decode(stream_get_contents(STDIN), true);
if (!is_array($payload)) { exit(1); }
echo $payload["data"]["access_token"] ?? $payload["access_token"] ?? "";
')"

if [[ -z "$ACCESS_TOKEN" ]]; then
  echo "Khong lay duoc access token. Kiem tra email/password hoac response API."
  exit 1
fi

echo
echo "=== Step 2: Create payment request for booking #$BOOKING_ID ==="
curl -i -X POST "$BASE_URL/api/customer/bookings/$BOOKING_ID/payment" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"method\":\"bank_transfer\",\"note\":\"$NOTE\"}"

if [[ "$SKIP_WEBHOOK" == "1" ]]; then
  echo
  echo "Webhook da duoc bo qua vi SKIP_WEBHOOK=1"
  exit 0
fi

echo
echo "=== Step 3: Simulate Seepay webhook confirmation ==="
curl -i -X POST "$BASE_URL/api/webhooks/seepay" \
  -H "Content-Type: application/json" \
  -d "{\n    \"content\": \"PLAY$BOOKING_ID\",\n    \"transferAmount\": $AMOUNT,\n    \"referenceCode\": \"$REFERENCE_CODE\"\n  }"

echo
echo "=== Step 4: Check booking status ==="
curl -i -X GET "$BASE_URL/api/customer/bookings/$BOOKING_ID" \
  -H "Authorization: Bearer $ACCESS_TOKEN"
