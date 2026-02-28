<?php

declare(strict_types=1);

$uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$path = (string) (parse_url($uri, PHP_URL_PATH) ?: '/');
$file = __DIR__ . $path;

// Biarkan built-in server melayani file statis di dalam webroot src.
if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
