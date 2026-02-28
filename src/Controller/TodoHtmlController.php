<?php

declare(strict_types=1);

namespace Controller;

use Http\Request;
use Http\Response;
use Todo\TodoService;
use Todo\ValidationException;
use View\View;

/**
 * Controller HTML server-rendered untuk halaman todo.
 */
final class TodoHtmlController
{
    private TodoService $service;
    private View $view;
    private TodoApiController $apiController;

    public function __construct(TodoService $service, View $view, TodoApiController $apiController)
    {
        $this->service = $service;
        $this->view = $view;
        $this->apiController = $apiController;
    }

    /**
     * Render daftar todo (atau JSON jika diminta).
     */
    public function index(Request $request, array $params = []): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->index($request, $params);
        }

        $html = $this->view->render('todos/index.php', [
            'title' => 'Daftar Todo',
            'todos' => $this->service->list(),
        ]);

        return Response::html($html);
    }

    /**
     * Render form create todo.
     */
    public function createForm(Request $request, array $params = []): Response
    {
        if ($request->wantsJson()) {
            return Response::json([
                'data' => [
                    'message' => 'Gunakan endpoint POST /api/todos untuk membuat todo.',
                    'fields' => ['title', 'description'],
                ],
            ]);
        }

        $html = $this->view->render('todos/create.php', [
            'title' => 'Tambah Todo',
            'formAction' => '/todos',
            'errors' => [],
            'old' => ['title' => '', 'description' => ''],
        ]);

        return Response::html($html);
    }

    /**
     * Menyimpan todo baru (PRG untuk HTML).
     */
    public function store(Request $request, array $params = []): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->store($request, $params);
        }

        $this->service->create($request->allInput());

        return Response::redirect('/todos');
    }

    /**
     * Render form edit todo.
     */
    public function editForm(Request $request, array $params): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->show($request, $params);
        }

        $todo = $this->service->getById($this->resolveId($params));

        $html = $this->view->render('todos/edit.php', [
            'title' => 'Edit Todo',
            'todo' => $todo,
            'errors' => [],
        ]);

        return Response::html($html);
    }

    /**
     * Memperbarui todo (PRG untuk HTML).
     */
    public function update(Request $request, array $params): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->update($request, $params);
        }

        $id = $this->resolveId($params);
        $this->service->update($id, $request->allInput());

        return Response::redirect('/todos');
    }

    /**
     * Menghapus todo (PRG untuk HTML).
     */
    public function destroy(Request $request, array $params): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->destroy($request, $params);
        }

        $this->service->delete($this->resolveId($params));

        return Response::redirect('/todos');
    }

    /**
     * Menandai todo selesai (PRG untuk HTML).
     */
    public function done(Request $request, array $params): Response
    {
        if ($request->wantsJson()) {
            return $this->apiController->done($request, $params);
        }

        $this->service->markDone($this->resolveId($params));

        return Response::redirect('/todos');
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
