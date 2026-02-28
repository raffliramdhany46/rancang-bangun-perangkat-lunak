<?php

declare(strict_types=1);

namespace Http;

/**
 * Representasi request HTTP yang telah diparse dari superglobal PHP.
 */
final class Request
{
    private string $method;
    private string $uri;
    private string $path;

    /** @var array<string, mixed> */
    private array $query;

    /** @var array<string, mixed> */
    private array $body;

    /** @var array<string, string> */
    private array $headers;

    private string $rawBody;

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $body
     * @param array<string, string> $headers
     */
    public function __construct(
        string $method,
        string $uri,
        string $path,
        array $query = [],
        array $body = [],
        array $headers = [],
        string $rawBody = ''
    ) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->path = $path === '' ? '/' : $path;
        $this->query = $query;
        $this->body = $body;
        $this->headers = $headers;
        $this->rawBody = $rawBody;
    }

    /**
     * Membuat instance request dari superglobal.
     */
    public static function fromGlobals(): self
    {
        $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $path = (string) (parse_url($uri, PHP_URL_PATH) ?: '/');
        $query = $_GET;
        $headers = self::collectHeaders();
        $rawBody = file_get_contents('php://input') ?: '';
        $originalMethod = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $body = self::parseBody($originalMethod, $headers, $rawBody);
        $method = self::resolveMethod($originalMethod, $body);

        return new self($method, $uri, $path, $query, $body, $headers, $rawBody);
    }

    /**
     * Method HTTP final setelah method spoofing.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * URI mentah dari request.
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Path request tanpa query string.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Mengambil nilai query parameter.
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Mengambil nilai input body.
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function allInput(): array
    {
        return $this->body;
    }

    /**
     * Mengambil nilai header secara case-insensitive.
     */
    public function header(string $name, string $default = ''): string
    {
        $normalized = strtolower($name);

        return $this->headers[$normalized] ?? $default;
    }

    /**
     * Menentukan apakah response sebaiknya JSON.
     */
    public function wantsJson(): bool
    {
        if (str_starts_with($this->path, '/api')) {
            return true;
        }

        if (strtolower((string) $this->query('format', '')) === 'json') {
            return true;
        }

        return str_contains(strtolower($this->header('accept')), 'application/json');
    }

    /**
     * @return array<string, string>
     */
    private static function collectHeaders(): array
    {
        $headers = [];

        if (function_exists('getallheaders')) {
            foreach ((array) getallheaders() as $name => $value) {
                $headers[strtolower((string) $name)] = (string) $value;
            }

            return $headers;
        }

        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }

            $name = strtolower(str_replace('_', '-', substr($key, 5)));
            $headers[$name] = (string) $value;
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = (string) $_SERVER['CONTENT_TYPE'];
        }

        return $headers;
    }

    /**
     * @param array<string, string> $headers
     * @return array<string, mixed>
     */
    private static function parseBody(string $method, array $headers, string $rawBody): array
    {
        if ($method === 'GET') {
            return [];
        }

        $contentType = strtolower($headers['content-type'] ?? '');

        if (str_contains($contentType, 'application/json')) {
            $decoded = json_decode($rawBody, true);

            return is_array($decoded) ? $decoded : [];
        }

        if ($method === 'POST') {
            return is_array($_POST) ? $_POST : [];
        }

        $parsed = [];
        parse_str($rawBody, $parsed);

        return is_array($parsed) ? $parsed : [];
    }

    /**
     * Menerapkan method spoofing via field _method pada request POST.
     *
     * @param array<string, mixed> $body
     */
    private static function resolveMethod(string $originalMethod, array $body): string
    {
        if ($originalMethod !== 'POST') {
            return $originalMethod;
        }

        $spoofed = strtoupper((string) ($body['_method'] ?? ''));
        $allowed = ['PUT', 'PATCH', 'DELETE'];

        if (in_array($spoofed, $allowed, true)) {
            return $spoofed;
        }

        return $originalMethod;
    }
}
