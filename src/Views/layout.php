<?php
/** @var string $content */
/** @var string|null $title */
$pageTitle = $title ?? 'Todo App';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .done { text-decoration: line-through; color: #6b7280; }
        .btn { border: 0; border-radius: 8px; padding: 8px 12px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-muted { background: #e5e7eb; color: #111827; }
        .btn-danger { background: #dc2626; color: #fff; }
        .field { margin-bottom: 12px; }
        .field label { display: block; margin-bottom: 6px; font-weight: 600; }
        .field input, .field textarea { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #d1d5db; border-radius: 8px; }
        .error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        .muted { color: #6b7280; font-size: 14px; }
        ul { padding-left: 16px; }
    </style>
</head>
<body>
<div class="container">
    <?= $content; ?>
</div>
<script src="/assets/app.js"></script>
</body>
</html>
