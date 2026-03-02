<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/View/View.php';

use App\View\View;

$db = db();

// 1) 5 task teratas yang paling dekat deadline (yang belum selesai, deadline tidak null)
$stmtUpcoming = $db->prepare("
    SELECT id, title, description, is_done, dead_line, is_need_achieve, created_at, updated_at
    FROM todos
    WHERE dead_line IS NOT NULL
      AND is_done = 0
    ORDER BY dead_line ASC
    LIMIT 5
");
$stmtUpcoming->execute();
$upcomingTodos = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

// 2) jumlah task selesai
$stmtDone = $db->prepare("SELECT COUNT(*) FROM todos WHERE is_done = 1");
$stmtDone->execute();
$doneCount = (int) $stmtDone->fetchColumn();

// 3) jumlah task overdue (deadline lewat, belum selesai)
$stmtOverdue = $db->prepare("
    SELECT COUNT(*)
    FROM todos
    WHERE dead_line IS NOT NULL
      AND dead_line < NOW()
      AND is_done = 0
");
$stmtOverdue->execute();
$overdueCount = (int) $stmtOverdue->fetchColumn();

View::render('dashboard', [
    'title' => 'Dashboard',
    'pageHeading' => 'Dashboard Todo',
    'doneCount' => $doneCount,
    'overdueCount' => $overdueCount,
    'upcomingTodos' => $upcomingTodos,
]);
