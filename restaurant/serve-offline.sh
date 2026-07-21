#!/bin/bash
# Start DzieRes Restaurant locally (offline-first)
# Requires PHP 8.1+ with SQLite extension enabled

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Ensure writable temp directory exists
mkdir -p /tmp/dzieres 2>/dev/null || true

# Start PHP built-in server
exec php -S localhost:8000 -t . server.php
