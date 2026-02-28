<?php

declare(strict_types=1);

namespace Todo;

/**
 * Exception untuk menandakan data input tidak valid.
 */
final class ValidationException extends \RuntimeException
{
    /** @var array<string, mixed> */
    private array $details;

    /**
     * @param array<string, mixed> $details
     */
    public function __construct(string $message, array $details = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
