<?php

declare(strict_types=1);
?>
<section class="card">
    <h2><?= e($pageHeading ?? 'About'); ?></h2>
    <p>Project ini memakai pola sederhana untuk pemula:</p>
    <ul>
        <li>URL langsung ke file PHP, tanpa router custom.</li>
        <li>Layout dipusatkan di <code>src/Views/layout.php</code>.</li>
        <li>Asset statis diletakkan di folder <code>src/assets</code>.</li>
    </ul>
</section>
