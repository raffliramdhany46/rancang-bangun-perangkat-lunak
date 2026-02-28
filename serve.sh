#!/usr/bin/env bash
set -euo pipefail

if ! command -v php >/dev/null 2>&1; then
  echo "Error: php command tidak ditemukan di PATH." >&2
  echo "Install PHP dulu, lalu jalankan ulang script ini." >&2
  exit 1
fi

HOST="127.0.0.1"
PORT="8080"

echo "Menjalankan PHP dev server di http://${HOST}:${PORT}"
php -S "${HOST}:${PORT}" -t src src/dev-router.php
