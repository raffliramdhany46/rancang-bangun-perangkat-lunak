<?php

declare(strict_types=1);

namespace Todo;

use Config;
use PDO;

/**
 * Factory untuk memilih implementasi repository todo berdasarkan konfigurasi.
 */
final class TodoRepositoryFactory
{
    /**
     * Membuat repository sesuai storage driver (`memory` atau `sql`).
     */
    public static function make(Config $config): TodoRepositoryInterface
    {
        $driver = strtolower((string) $config->get('todo.storage_driver', 'memory'));

        if ($driver === 'sql') {
            return new PdoTodoRepository(self::createPdo($config));
        }

        return new MemoryTodoRepository();
    }

    /**
     * Membuat koneksi PDO untuk MySQL/MariaDB.
     */
    private static function createPdo(Config $config): PDO
    {
        $host = (string) $config->get('db.host', '127.0.0.1');
        $port = (int) $config->get('db.port', 3306);
        $database = (string) $config->get('db.database', 'todo_app');
        $username = (string) $config->get('db.username', 'root');
        $password = (string) $config->get('db.password', '');
        $charset = (string) $config->get('db.charset', 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $database, $charset);

        return new PDO($dsn, $username, $password);
    }
}
