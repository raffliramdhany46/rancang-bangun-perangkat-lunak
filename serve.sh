#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOCROOT="${SCRIPT_DIR}/src"
ROUTER="${DOCROOT}/dev-router.php"
HOST="localhost"
PORT="8000"

PHP_BIN="${PHP_BIN:-}"

if [[ -z "${PHP_BIN}" ]] && command -v php >/dev/null 2>&1; then
  PHP_BIN="$(command -v php)"
fi

if [[ -z "${PHP_BIN}" ]] && command -v php.exe >/dev/null 2>&1; then
  PHP_BIN="$(command -v php.exe)"
fi

if [[ -z "${PHP_BIN}" ]] && command -v powershell.exe >/dev/null 2>&1; then
  PHP_BIN="$(powershell.exe -NoProfile -Command '$c = Get-Command php -ErrorAction SilentlyContinue; if ($c) { $c.Source }' | tr -d '\r')"
fi

if [[ -z "${PHP_BIN}" ]]; then
  echo "Error: php command tidak ditemukan." >&2
  echo "Jalankan langsung dari PowerShell: php -S localhost:8000 -t .\\src .\\src\\dev-router.php" >&2
  exit 1
fi

if [[ ! -d "${DOCROOT}" ]]; then
  echo "Error: folder docroot tidak ditemukan: ${DOCROOT}" >&2
  exit 1
fi

if [[ ! -f "${ROUTER}" ]]; then
  echo "Error: file router tidak ditemukan: ${ROUTER}" >&2
  exit 1
fi

echo "Menjalankan PHP dev server di http://${HOST}:${PORT}"
"${PHP_BIN}" -S "${HOST}:${PORT}" -t "${DOCROOT}" "${ROUTER}"
