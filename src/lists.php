<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/View/View.php';

use App\View\View;

$db = db();

/*
|--------------------------------------------------------------------------
| Query Params
|--------------------------------------------------------------------------
*/
$sort     = $_GET['sort'] ?? 'latest';
$status   = $_GET['status'] ?? 'all';
$search   = trim($_GET['search'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 5;
$offset   = ($page - 1) * $perPage;

/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/
switch ($sort) {
    case 'oldest':
        $orderBy = 'created_at ASC';
        break;

    case 'deadline':
        $orderBy = 'dead_line IS NULL, dead_line ASC';
        break;

    case 'latest':
    default:
        $orderBy = 'created_at DESC';
        $sort = 'latest';
        break;
}

/*
|--------------------------------------------------------------------------
| Filtering
|--------------------------------------------------------------------------
*/
$where = [];
$params = [];

if ($status === 'done') {
    $where[] = 'is_done = 1';
}
elseif ($status === 'pending') {
    $where[] = 'is_done = 0';
}
elseif ($status === 'overdue') {
    $where[] = 'is_done = 0 AND dead_line IS NOT NULL AND dead_line < NOW()';
}

if ($search !== '') {
    $where[] = 'title LIKE :search';
    $params['search'] = '%' . $search . '%';
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

/*
|--------------------------------------------------------------------------
| Count Total Rows
|--------------------------------------------------------------------------
*/
$countStmt = $db->prepare("
    SELECT COUNT(*) FROM todos
    $whereSql
");
$countStmt->execute($params);

$totalRows  = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $perPage));

/*
|--------------------------------------------------------------------------
| Fetch Data
|--------------------------------------------------------------------------
*/
$stmt = $db->prepare("
    SELECT *
    FROM todos
    $whereSql
    ORDER BY $orderBy
    LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $value) {
    $stmt->bindValue(':' . $key, $value);
}

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

View::render('lists', [
    'title' => 'All Lists',
    'pageHeading' => 'Daftar Todo',
    'lists' => $lists,
    'currentSort' => $sort,
    'currentStatus' => $status,
    'currentSearch' => $search,
    'page' => $page,
    'totalPages' => $totalPages,
]);