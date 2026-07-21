#!/bin/bash
# Start DzieRes locally with PHP built-in server.
# Usage: bash start-local.sh [port]
# Default port is 8000.

PORT=${1:-8000}
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR/restaurant"

mkdir -p /tmp/dzieres 2>/dev/null || true

echo "Starting DzieRes at http://localhost:$PORT"
php -S localhost:$PORT -t . server.php
