@echo off
setlocal

set "SCRIPT_DIR=%~dp0"
set "DOCROOT=%SCRIPT_DIR%src"
set "HOST=127.0.0.1"
set "PORT=8080"

where php >nul 2>nul
if errorlevel 1 (
  echo Error: php command tidak ditemukan di PATH.
  echo Jalankan dari Laragon Terminal atau tambahkan php ke PATH.
  exit /b 1
)

if not exist "%DOCROOT%" (
  echo Error: folder docroot tidak ditemukan: %DOCROOT%
  exit /b 1
)

echo Menjalankan PHP dev server di http://%HOST%:%PORT%
php -S %HOST%:%PORT% -t "%DOCROOT%"
endlocal
