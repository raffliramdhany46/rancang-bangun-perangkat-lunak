<?php

declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../View/View.php';

use App\View\View;

final class TodoHtmlController
{
    private function jsonResponse(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode($payload);
    }

    public function create(): void
    {
        View::render('todos/create', [
            'title' => 'Buat Todo',
            'pageHeading' => 'Form Tambah Todo',
        ]);
    }

    public function store(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST') {
            $title = $_POST['title'] ?? 'Untitled';
            echo "Todo dengan judul '{$title}' telah disimpan.";
            exit();
        }
    }

    public function edit(): void
    {
        $db = db();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo "Parameter id tidak valid.";
            exit;
        }

        $stmt = $db->prepare("
            SELECT id, title, description, is_done, dead_line, is_need_achieve
            FROM todos
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);

        $todo = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$todo) {
            http_response_code(404);
            echo "Todo tidak ditemukan.";
            exit;
        }

        View::render('edit', [
            'title' => 'Edit Todo',
            'pageHeading' => 'Edit Todo',
            'todo' => $todo,
        ]);
    }

    public function update(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method !== 'POST') {
            http_response_code(405);
            echo "Method not allowed.";
            exit;
        }

        $db = db();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo "Parameter id tidak valid.";
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        // checkbox: kalau tidak dicentang -> tidak muncul di POST
        $isDone = isset($_POST['is_done']) ? 1 : 0;
        $isNeedAchieve = isset($_POST['is_need_achieve']) ? 1 : 0;

        // datetime-local biasanya "YYYY-mm-ddTHH:ii"
        $deadLineRaw = trim((string)($_POST['dead_line'] ?? ''));
        $deadLine = null;
        if ($deadLineRaw !== '') {
            // ubah jadi format MySQL "YYYY-mm-dd HH:ii:ss"
            $deadLine = str_replace('T', ' ', $deadLineRaw) . ':00';
        }

        if ($title === '' || $description === '') {
            http_response_code(422);
            echo "Title dan Description wajib diisi.";
            exit;
        }

        $stmt = $db->prepare("
            UPDATE todos
            SET title = :title,
                description = :description,
                is_done = :is_done,
                is_need_achieve = :is_need_achieve,
                dead_line = :dead_line
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'is_done' => $isDone,
            'is_need_achieve' => $isNeedAchieve,
            'dead_line' => $deadLine, // null boleh
            'id' => $id,
        ]);

        header('Location: /dashboard.php');
        exit;
    }

    public function markDone(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method !== 'POST') {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Method not allowed.',
            ], 405);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Parameter id tidak valid.',
            ], 422);
            return;
        }

        try {
            $db = db();

            $stmtTodo = $db->prepare("
                SELECT id, is_done
                FROM todos
                WHERE id = :id
                LIMIT 1
            ");
            $stmtTodo->execute(['id' => $id]);
            $todo = $stmtTodo->fetch(\PDO::FETCH_ASSOC);

            if (!$todo) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Todo tidak ditemukan.',
                ], 404);
                return;
            }

            if ((int)$todo['is_done'] !== 1) {
                $stmtUpdate = $db->prepare("
                    UPDATE todos
                    SET is_done = 1
                    WHERE id = :id
                    LIMIT 1
                ");
                $stmtUpdate->execute(['id' => $id]);
            }

            $this->jsonResponse([
                'success' => true,
                'message' => 'Todo berhasil ditandai selesai.',
                'id' => $id,
                'is_done' => 1,
            ]);
        } catch (\Throwable $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Gagal update todo.',
            ], 500);
        }
    }
}
