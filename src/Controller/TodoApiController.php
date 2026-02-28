<?php

declare(strict_types=1);

namespace Controller;

use Http\Request;
use Http\Response;
use Todo\TodoService;
use Todo\ValidationException;

/**
 * Controller API JSON untuk resource todo.
 */
final class TodoApiController
{
    private TodoService $service;

    public function __construct(TodoService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar todo dalam format JSON.
     */
    public function index(Request $request, array $params = []): Response
    {
        return Response::json(['data' => $this->service->list()]);
    }

    /**
     * Menampilkan detail todo JSON berdasarkan ID.
     */
    public function show(Request $request, array $params): Response
    {
        return Response::json(['data' => $this->service->getById($this->resolveId($params))]);
    }

    /**
     * Menyimpan todo baru dan mengembalikan JSON.
     */
    public function store(Request $request, array $params = []): Response
    {
        $todo = $this->service->create($request->allInput());

        return Response::json(['data' => $todo], 201);
    }

    /**
     * Memperbarui todo dan mengembalikan JSON.
     */
    public function update(Request $request, array $params): Response
    {
        $todo = $this->service->update($this->resolveId($params), $request->allInput());

        return Response::json(['data' => $todo]);
    }

    /**
     * Menghapus todo dan mengembalikan status JSON.
     */
    public function destroy(Request $request, array $params): Response
    {
        $this->service->delete($this->resolveId($params));

        return Response::json(['data' => ['deleted' => true]]);
    }

    /**
     * Menandai todo selesai dan mengembalikan JSON.
     */
    public function done(Request $request, array $params): Response
    {
        return Response::json(['data' => $this->service->markDone($this->resolveId($params))]);
    }

    /**
     * Mengonversi parameter route id ke integer valid.
     */
    private function resolveId(array $params): int
    {
        $id = (int) ($params['id'] ?? 0);

        if ($id < 1) {
            throw new ValidationException('ID todo tidak valid.', ['id' => 'ID harus berupa angka positif.']);
        }

        return $id;
    }
}
