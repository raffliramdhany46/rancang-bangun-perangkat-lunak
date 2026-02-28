<?php

declare(strict_types=1);

namespace Todo;

/**
 * Kontrak repository data todo.
 */
interface TodoRepositoryInterface
{
    /**
     * Mengambil seluruh data todo.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listAll(): array;

    /**
     * Mengambil todo berdasarkan ID.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array;

    /**
     * Menyimpan todo baru.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array;

    /**
     * Memperbarui todo berdasarkan ID.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    public function update(int $id, array $data): ?array;

    /**
     * Menghapus todo berdasarkan ID.
     */
    public function delete(int $id): bool;

    /**
     * Menandai todo selesai.
     *
     * @return array<string, mixed>|null
     */
    public function markDone(int $id): ?array;
}
