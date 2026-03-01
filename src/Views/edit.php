<?php

declare(strict_types=1);
?>
<section class="card">
    <h2><?= e($pageHeading ?? 'Home'); ?></h2>
    <p>HALAMAN INI ADALAH UNTUK BASE EDIT TODO LIST</p>
    <p>Buka halaman form todo di menu <strong>Create Todo</strong>.</p>
    <?php foreach ($todos ?? [] as $todo): ?>
        <p><?= e($todo['title'] ?? 'No Title') ?></p>
    <?php endforeach; ?>
</section>