<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../View/View.php';

use App\View\View;

$db = db();

$errors = [];
$old = [
    'title' => '',
    'description' => '',
    'dead_line' => '',
    'is_need_achieve' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old['title'] = trim($_POST['title'] ?? '');
    $old['description'] = trim($_POST['description'] ?? '');
    $old['dead_line'] = $_POST['dead_line'] ?? null;
    $old['is_need_achieve'] = isset($_POST['is_need_achieve']) ? 1 : 0;

    // Validasi
    if ($old['title'] === '') {
        $errors['title'] = 'Title wajib diisi';
    }

    if ($old['description'] === '') {
        $errors['description'] = 'Description wajib diisi';
    }

    if (empty($errors)) {

        $stmt = $db->prepare("
            INSERT INTO todos (title, description, dead_line, is_need_achieve)
            VALUES (:title, :description, :dead_line, :is_need_achieve)
        ");

        $stmt->execute([
            'title' => $old['title'],
            'description' => $old['description'],
            'dead_line' => $old['dead_line'] ?: null,
            'is_need_achieve' => $old['is_need_achieve'],
        ]);

        header('Location: /dashboard.php');
        exit;
    }
}

View::render('todos/create', [
    'title' => 'Create Todo',
    'pageHeading' => 'Tambah Todo Baru',
    'errors' => $errors,
    'old' => $old,
]);