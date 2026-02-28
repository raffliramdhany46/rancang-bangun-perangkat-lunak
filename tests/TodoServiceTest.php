<?php

declare(strict_types=1);

use Todo\MemoryTodoRepository;
use Todo\TodoNotFoundException;
use Todo\TodoService;
use Todo\ValidationException;

/**
 * Pengujian service todo: validasi dan not-found scenario.
 */
final class TodoServiceTest extends TestCase
{
    public function testCreateValidationFailsWhenTitleEmpty(): void
    {
        $_SESSION = [];

        $service = new TodoService(new MemoryTodoRepository());

        $this->assertThrows(
            static fn (): array => $service->create(['title' => '   ', 'description' => 'x']),
            ValidationException::class
        );
    }

    public function testCreateAndUpdateSuccess(): void
    {
        $_SESSION = [];

        $service = new TodoService(new MemoryTodoRepository());
        $created = $service->create(['title' => 'Belajar RBPL', 'description' => 'Hybrid app']);
        $updated = $service->update((int) $created['id'], ['title' => 'Belajar RBPL 2', 'description' => 'Updated']);

        $this->assertSame('Belajar RBPL 2', $updated['title']);
    }

    public function testDeleteUnknownTodoThrowsNotFound(): void
    {
        $_SESSION = [];

        $service = new TodoService(new MemoryTodoRepository());

        $this->assertThrows(
            static function () use ($service): void {
                $service->delete(999);
            },
            TodoNotFoundException::class
        );
    }
}
