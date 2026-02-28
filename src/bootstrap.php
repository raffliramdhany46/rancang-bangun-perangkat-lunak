<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Asia/Bangkok');

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
