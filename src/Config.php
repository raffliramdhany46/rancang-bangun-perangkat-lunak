<?php

declare(strict_types=1);

/**
 * Menyediakan akses konfigurasi aplikasi berbasis array dengan dot notation.
 */
final class Config
{
    /** @var array<string, mixed> */
    private array $items;

    /**
     * @param array<string, mixed> $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Memuat konfigurasi dari file PHP yang mengembalikan array.
     *
     * @param string $filePath Path absolut file konfigurasi.
     */
    public static function load(string $filePath): self
    {
        $items = require $filePath;

        if (!is_array($items)) {
            $items = [];
        }

        return new self($items);
    }

    /**
     * Mengambil nilai konfigurasi menggunakan dot notation.
     *
     * Contoh: get('db.host', '127.0.0.1')
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = $this->items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}
