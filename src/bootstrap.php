<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Asia/Bangkok');

// Muat environment dari src/.env jika tersedia (tanpa menimpa env yang sudah ada).
$dotenvPath = __DIR__ . '/.env';
if (is_file($dotenvPath) && is_readable($dotenvPath)) {
    if (!isset($_ENV) || !is_array($_ENV)) {
        $_ENV = [];
    }

    $lines = file($dotenvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_starts_with($line, 'export ')) {
                $line = trim(substr($line, 7));
            }

            $separator = strpos($line, '=');

            if ($separator === false) {
                continue;
            }

            $name = trim(substr($line, 0, $separator));

            if ($name === '' || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
                continue;
            }

            if (array_key_exists($name, $_ENV) || array_key_exists($name, $_SERVER) || getenv($name) !== false) {
                continue;
            }

            $value = trim(substr($line, $separator + 1));

            if ($value !== '') {
                $isDoubleQuoted = str_starts_with($value, '"') && str_ends_with($value, '"');
                $isSingleQuoted = str_starts_with($value, "'") && str_ends_with($value, "'");

                if ($isDoubleQuoted || $isSingleQuoted) {
                    $value = substr($value, 1, -1);

                    if ($isDoubleQuoted) {
                        $value = str_replace(
                            ['\\n', '\\r', '\\t', '\\"', '\\\\'],
                            ["\n", "\r", "\t", '"', '\\'],
                            $value
                        );
                    }
                } else {
                    $value = preg_replace('/\s+#.*$/', '', $value) ?? $value;
                    $value = trim($value);
                }
            }

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv($name . '=' . $value);
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(static function (string $class): void {
    $relativePath = str_replace('\\', '/', $class) . '.php';
    $absolutePath = __DIR__ . '/' . $relativePath;

    if (is_file($absolutePath)) {
        require_once $absolutePath;
    }
});
