<?php

declare(strict_types=1);

namespace Todo;

/**
 * Exception untuk kasus todo tidak ditemukan.
 */
final class TodoNotFoundException extends \RuntimeException
{
    private int $todoId;

    public function __construct(int $todoId)
    {
        parent::__construct('Todo dengan ID ' . $todoId . ' tidak ditemukan.');
        $this->todoId = $todoId;
    }

    /**
     * Mengambil ID todo yang tidak ditemukan.
     */
    public function getTodoId(): int
    {
        return $this->todoId;
    }
}
