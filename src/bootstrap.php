<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Asia/Jakarta');

if (!function_exists('config')) {
    function config(?string $key = null, mixed $default = null): mixed
    {
        static $config = null;

        if ($config === null) {
            $configFile = __DIR__ . '/config.php';

            if (!is_file($configFile)) {
                throw new RuntimeException('File config tidak ditemukan: ' . $configFile);
            }

            $loaded = require $configFile;
            $config = is_array($loaded) ? $loaded : [];
        }

        if ($key === null || $key === '') {
            return $config;
        }

        $segments = explode('.', $key);
        $value = $config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('db')) {
    function db(): PDO
    {
        static $pdo = null;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $host = (string) config('db.host', '127.0.0.1');
        $port = (int) config('db.port', 3306);
        $name = (string) config('db.name', '');
        $user = (string) config('db.user', '');
        $pass = (string) config('db.pass', '');
        $charset = (string) config('db.charset', 'utf8mb4');

        if ($name === '') {
            throw new RuntimeException('Config database `db.name` belum diisi.');
        }

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $name, $charset);

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;
    }
}

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

