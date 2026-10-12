<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../View/View.php';

use App\View\View;

$db = db();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$redirect = $_GET['redirect'] ?? 'dashboard';

$redirectTo = '/dashboard.php';
if ($redirect === 'lists') {
    $redirectTo = '/lists.php';
}

if ($id <= 0) {
    http_response_code(400);
    echo 'Parameter id tidak valid.';
    exit;
}

if ($method === 'POST') {
    $stmt = $db->prepare("DELETE FROM todos WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);

    header('Location: ' . $redirectTo);
    exit;
}

$stmt = $db->prepare("SELECT id, title FROM todos WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    http_response_code(404);
    echo 'Todo tidak ditemukan.';
    exit;
}

View::render('todos/delete', [
    'title' => 'Hapus Todo',
    'pageHeading' => 'Hapus Todo',
    'todo' => $todo,
]);
