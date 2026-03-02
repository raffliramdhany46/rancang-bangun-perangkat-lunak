<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/View/View.php';

use App\View\View;

$db = db();

// routing dari query string: /edit.php?id=123
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

$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    http_response_code(404);
    echo "Todo tidak ditemukan.";
    exit;
}

View::render('edit', [
    'title' => 'Edit Todo',
    'pageHeading' => 'Edit Todo',
    'todo' => $todo, // 1 todo, bukan array list
]);