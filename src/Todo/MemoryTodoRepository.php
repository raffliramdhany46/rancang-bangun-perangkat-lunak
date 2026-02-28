<?php

declare(strict_types=1);

namespace Todo;

/**
 * Repository todo berbasis session memory untuk kebutuhan lokal tanpa SQL.
 */
final class MemoryTodoRepository implements TodoRepositoryInterface
{
    private const TODOS_KEY = 'todo_memory_items';
    private const LAST_ID_KEY = 'todo_memory_last_id';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(): array
    {
        $todos = array_values($this->todos());

        usort(
            $todos,
            static fn (array $a, array $b): int => (int) $b['id'] <=> (int) $a['id']
        );

        return $todos;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $todos = $this->todos();

        return $todos[$id] ?? null;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $todos = &$this->todos();
        $id = $this->nextId();
        $now = date('Y-m-d H:i:s');

        $todo = [
            'id' => $id,
            'title' => (string) $data['title'],
            'description' => (string) ($data['description'] ?? ''),
            'is_done' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $todos[$id] = $todo;

        return $todo;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    public function update(int $id, array $data): ?array
    {
        $todos = &$this->todos();

        if (!isset($todos[$id])) {
            return null;
        }

        $current = $todos[$id];
        $updated = [
            'id' => $id,
            'title' => (string) ($data['title'] ?? $current['title']),
            'description' => (string) ($data['description'] ?? $current['description']),
            'is_done' => isset($data['is_done']) ? (int) ((bool) $data['is_done']) : (int) $current['is_done'],
            'created_at' => (string) $current['created_at'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $todos[$id] = $updated;

        return $updated;
    }

    public function delete(int $id): bool
    {
        $todos = &$this->todos();

        if (!isset($todos[$id])) {
            return false;
        }

        unset($todos[$id]);

        return true;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function markDone(int $id): ?array
    {
        return $this->update($id, ['is_done' => 1]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function &todos(): array
    {
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }

        if (!isset($_SESSION[self::TODOS_KEY]) || !is_array($_SESSION[self::TODOS_KEY])) {
            $_SESSION[self::TODOS_KEY] = [];
        }

        return $_SESSION[self::TODOS_KEY];
    }

    private function nextId(): int
    {
        if (!isset($_SESSION[self::LAST_ID_KEY])) {
            $_SESSION[self::LAST_ID_KEY] = 0;
        }

        $_SESSION[self::LAST_ID_KEY] = (int) $_SESSION[self::LAST_ID_KEY] + 1;

        return (int) $_SESSION[self::LAST_ID_KEY];
    }
}
