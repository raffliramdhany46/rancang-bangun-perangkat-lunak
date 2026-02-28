<?php

declare(strict_types=1);

$env = static function (string $key, mixed $default = null): mixed {
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }

    if (array_key_exists($key, $_SERVER)) {
        return $_SERVER[$key];
    }

    $value = getenv($key);

    return $value === false ? $default : $value;
};

return [
    'app' => [
        'name' => 'Todo Hybrid',
        'debug' => filter_var((string) $env('APP_DEBUG', '1'), FILTER_VALIDATE_BOOL),
    ],
    'todo' => [
        'storage_driver' => strtolower((string) $env('TODO_STORAGE_DRIVER', 'memory')),
    ],
    'db' => [
        'host' => (string) $env('TODO_DB_HOST', '127.0.0.1'),
        'port' => (int) $env('TODO_DB_PORT', 3306),
        'database' => (string) $env('TODO_DB_NAME', 'todo_app'),
        'username' => (string) $env('TODO_DB_USER', 'root'),
        'password' => (string) $env('TODO_DB_PASS', ''),
        'charset' => (string) $env('TODO_DB_CHARSET', 'utf8mb4'),
    ],
];
