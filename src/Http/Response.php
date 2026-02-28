<?php

declare(strict_types=1);

namespace Http;

/**
 * Representasi response HTTP dengan helper JSON, HTML, dan redirect.
 */
final class Response
{
    private int $statusCode;

    /** @var array<string, string> */
    private array $headers;

    private string $body;

    /**
     * @param array<string, string> $headers
     */
    public function __construct(string $body = '', int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Membuat response JSON.
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public static function json(array $data, int $statusCode = 200, array $headers = []): self
    {
        $headers = array_merge(['Content-Type' => 'application/json; charset=UTF-8'], $headers);
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new self($payload === false ? '{}' : $payload, $statusCode, $headers);
    }

    /**
     * Membuat response HTML.
     *
     * @param array<string, string> $headers
     */
    public static function html(string $html, int $statusCode = 200, array $headers = []): self
    {
        $headers = array_merge(['Content-Type' => 'text/html; charset=UTF-8'], $headers);

        return new self($html, $statusCode, $headers);
    }

    /**
     * Membuat response redirect.
     */
    public static function redirect(string $location, int $statusCode = 302): self
    {
        return new self('', $statusCode, ['Location' => $location]);
    }

    /**
     * Mengirim response ke client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->body;
    }

    /**
     * Mengambil status code untuk kebutuhan test.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Mengambil body response untuk kebutuhan test.
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
