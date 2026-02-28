<?php

declare(strict_types=1);

namespace Todo;

/**
 * Service domain todo: validasi input dan orkestrasi operasi repository.
 */
final class TodoService
{
    private TodoRepositoryInterface $repository;

    public function __construct(TodoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil seluruh todo.
     *
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        return $this->repository->listAll();
    }

    /**
     * Mengambil detail todo berdasarkan ID.
     *
     * @return array<string, mixed>
     */
    public function getById(int $id): array
    {
        $todo = $this->repository->findById($id);

        if ($todo === null) {
            throw new TodoNotFoundException($id);
        }

        return $todo;
    }

    /**
     * Membuat todo baru.
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function create(array $input): array
    {
        $payload = $this->validateCreatePayload($input);

        return $this->repository->create($payload);
    }

    /**
     * Memperbarui todo berdasarkan ID.
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function update(int $id, array $input): array
    {
        $existing = $this->repository->findById($id);

        if ($existing === null) {
            throw new TodoNotFoundException($id);
        }

        $payload = $this->validateUpdatePayload($input, $existing);
        $updated = $this->repository->update($id, $payload);

        if ($updated === null) {
            throw new TodoNotFoundException($id);
        }

        return $updated;
    }

    /**
     * Menghapus todo berdasarkan ID.
     */
    public function delete(int $id): void
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            throw new TodoNotFoundException($id);
        }
    }

    /**
     * Menandai todo selesai berdasarkan ID.
     *
     * @return array<string, mixed>
     */
    public function markDone(int $id): array
    {
        $todo = $this->repository->markDone($id);

        if ($todo === null) {
            throw new TodoNotFoundException($id);
        }

        return $todo;
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function validateCreatePayload(array $input): array
    {
        $title = trim((string) ($input['title'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $errors = [];

        if ($title === '') {
            $errors['title'] = 'Judul wajib diisi.';
        } elseif (mb_strlen($title) > 120) {
            $errors['title'] = 'Judul maksimal 120 karakter.';
        }

        if (mb_strlen($description) > 1000) {
            $errors['description'] = 'Deskripsi maksimal 1000 karakter.';
        }

        if ($errors !== []) {
            throw new ValidationException('Validasi gagal.', $errors);
        }

        return [
            'title' => $title,
            'description' => $description,
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @param array<string, mixed> $existing
     * @return array<string, mixed>
     */
    private function validateUpdatePayload(array $input, array $existing): array
    {
        $title = array_key_exists('title', $input)
            ? trim((string) $input['title'])
            : (string) $existing['title'];

        $description = array_key_exists('description', $input)
            ? trim((string) $input['description'])
            : (string) $existing['description'];

        $errors = [];

        if ($title === '') {
            $errors['title'] = 'Judul wajib diisi.';
        } elseif (mb_strlen($title) > 120) {
            $errors['title'] = 'Judul maksimal 120 karakter.';
        }

        if (mb_strlen($description) > 1000) {
            $errors['description'] = 'Deskripsi maksimal 1000 karakter.';
        }

        if ($errors !== []) {
            throw new ValidationException('Validasi gagal.', $errors);
        }

        return [
            'title' => $title,
            'description' => $description,
            'is_done' => (int) ($existing['is_done'] ?? 0),
        ];
    }
}
