<?php

declare(strict_types=1);

namespace Todo;

use PDO;

/**
 * Repository todo berbasis PDO MySQL/MariaDB.
 */
final class PdoTodoRepository implements TodoRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(): array
    {
        $statement = $this->pdo->query('SELECT id, title, description, is_done, created_at, updated_at FROM todos ORDER BY id DESC');
        $rows = $statement->fetchAll();

        if (!is_array($rows)) {
            return [];
        }

        return array_map([$this, 'hydrate'], $rows);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, title, description, is_done, created_at, updated_at FROM todos WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        if (!is_array($row)) {
            return null;
        }

        return $this->hydrate($row);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO todos (title, description, is_done, created_at, updated_at) VALUES (:title, :description, 0, NOW(), NOW())'
        );

        $statement->execute([
            'title' => (string) $data['title'],
            'description' => (string) ($data['description'] ?? ''),
        ]);

        return $this->findById((int) $this->pdo->lastInsertId()) ?? [];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    public function update(int $id, array $data): ?array
    {
        $existing = $this->findById($id);

        if ($existing === null) {
            return null;
        }

        $statement = $this->pdo->prepare(
            'UPDATE todos SET title = :title, description = :description, is_done = :is_done, updated_at = NOW() WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'title' => (string) ($data['title'] ?? $existing['title']),
            'description' => (string) ($data['description'] ?? $existing['description']),
            'is_done' => isset($data['is_done']) ? (int) ((bool) $data['is_done']) : (int) $existing['is_done'],
        ]);

        return $this->findById($id);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM todos WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function markDone(int $id): ?array
    {
        $statement = $this->pdo->prepare('UPDATE todos SET is_done = 1, updated_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $id]);

        if ($statement->rowCount() === 0 && $this->findById($id) === null) {
            return null;
        }

        return $this->findById($id);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function hydrate(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'title' => (string) $row['title'],
            'description' => (string) $row['description'],
            'is_done' => (int) $row['is_done'],
            'created_at' => (string) $row['created_at'],
            'updated_at' => (string) $row['updated_at'],
        ];
    }
}
