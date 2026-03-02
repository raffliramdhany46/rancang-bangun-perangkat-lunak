<?php

declare(strict_types=1);

$title = $title ?? 'PHP Native App';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title); ?></title>
    <link rel="stylesheet" href="<?= e(asset('app.css')); ?>">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="brand">Rancang Bangun Perangkat Lunak Management versi (version control)</h1>
        <nav>
            <a href="/index.php">Home</a>
            <a href="/about.php">About</a>
            <a href="/lists.php">All Lists</a>
            <a href="/todos/create.php">Create Todo</a>
            <a href="/dashboard.php">Dashboard</a>
        </nav>
    </div>
</header>

<main class="container">
    <?= $content; ?>
</main>
</body>
</html>
