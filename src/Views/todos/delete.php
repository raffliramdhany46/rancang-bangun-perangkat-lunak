<?php
declare(strict_types=1);

$todo = $todo ?? null;
if (!$todo) {
    echo '<div class="card">Todo tidak ditemukan.</div>';
    return;
}
?>

<section class="card" style="max-width:700px; margin:auto;">
    <h2><?= e($pageHeading ?? 'Hapus Todo') ?></h2>

    <p>Anda akan menghapus todo berikut:</p>
    <div style="padding:12px; border:1px solid #eee; border-radius:8px; margin-bottom:12px;">
        <div style="font-weight:700;"><?= e($todo['title'] ?? '') ?></div>
    </div>

    <form method="POST" style="display:flex; gap:10px;">
        <button type="submit" class="btn btn-danger">Hapus</button>
        <a href="/dashboard.php" class="btn" style="text-decoration:none; padding:8px 12px; border-radius:6px;">Batal</a>
    </form>
</section>