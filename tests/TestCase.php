<?php

declare(strict_types=1);

/**
 * Base test case ringan tanpa dependency eksternal.
 */
abstract class TestCase
{
    /** @var array<int, string> */
    private array $failures = [];

    /**
     * Menjalankan seluruh method test* pada class turunan.
     */
    public function run(): void
    {
        $methods = get_class_methods($this);

        foreach ($methods as $method) {
            if (!str_starts_with($method, 'test')) {
                continue;
            }

            try {
                $this->{$method}();
                echo '[PASS] ' . static::class . '::' . $method . PHP_EOL;
            } catch (Throwable $throwable) {
                $message = '[FAIL] ' . static::class . '::' . $method . ' - ' . $throwable->getMessage();
                $this->failures[] = $message;
                echo $message . PHP_EOL;
            }
        }
    }

    /**
     * Menghentikan proses dengan code non-zero jika ada kegagalan.
     */
    public function finalize(): void
    {
        if ($this->failures !== []) {
            exit(1);
        }
    }

    /**
     * Assert dua nilai identik.
     */
    protected function assertSame(mixed $expected, mixed $actual, string $message = 'Nilai tidak sama.'): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException($message . ' expected=' . var_export($expected, true) . ' actual=' . var_export($actual, true));
        }
    }

    /**
     * Assert kondisi true.
     */
    protected function assertTrue(bool $condition, string $message = 'Kondisi bernilai false.'): void
    {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    }

    /**
     * Assert callable melempar exception tertentu.
     */
    protected function assertThrows(callable $callable, string $expectedExceptionClass): void
    {
        try {
            $callable();
        } catch (Throwable $throwable) {
            if ($throwable instanceof $expectedExceptionClass) {
                return;
            }

            throw new RuntimeException(
                'Exception tidak sesuai. expected=' . $expectedExceptionClass . ' actual=' . $throwable::class
            );
        }

        throw new RuntimeException('Exception tidak dilempar. expected=' . $expectedExceptionClass);
    }
}
