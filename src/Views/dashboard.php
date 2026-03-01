<?php

declare(strict_types=1);
?>
<section class="card">
    <h2><?= e($pageHeading ?? 'Home'); ?></h2>
    <p>HALAMAN INI ADALAH UNTUK BASE DASHBOARD</p>
    <p>Buka halaman form todo di menu <strong>Create Todo</strong>.</p>
    <p>Presiden saat ini: <?php printf("%s", $presiden ?? "Tidak Diketahui"); ?></p>
    <p>Angka: <?php echo implode(", ", $angka ?? []); ?></p>

</section>