<?php

declare(strict_types=1);

$lists         = $lists ?? [];
$currentSort   = $currentSort ?? 'latest';
$currentStatus = $currentStatus ?? 'all';
$currentSearch = $currentSearch ?? '';
$page          = $page ?? 1;
$totalPages    = $totalPages ?? 1;

function formatDate(?string $date): string {
    if (!$date) return '-';
    try {
        return (new DateTime($date))->format('d M Y H:i');
    } catch (Throwable $e) {
        return $date;
    }
}

function isOverdue(array $item): bool {
    if (!$item['dead_line'] || $item['is_done']) return false;
    return strtotime($item['dead_line']) < time();
}

$queryBase = http_build_query([
    'sort' => $currentSort,
    'status' => $currentStatus,
    'search' => $currentSearch,
]);
?>

<section class="card">

    <h2><?= e($pageHeading); ?></h2>

    <!-- FILTER BAR -->
    <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">

        <input type="text" name="search"
            value="<?= e($currentSearch) ?>"
            placeholder="Search title..."
            style="padding:8px; border-radius:8px; border:1px solid rgba(0,0,0,.2);">

        <select name="status" onchange="this.form.submit()" style="padding:8px; border-radius:8px;">
            <option value="all" <?= $currentStatus === 'all' ? 'selected' : '' ?>>Semua</option>
            <option value="done" <?= $currentStatus === 'done' ? 'selected' : '' ?>>Selesai</option>
            <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Belum</option>
            <option value="overdue" <?= $currentStatus === 'overdue' ? 'selected' : '' ?>>Overdue</option>
        </select>

        <select name="sort" onchange="this.form.submit()" style="padding:8px; border-radius:8px;">
            <option value="latest" <?= $currentSort === 'latest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $currentSort === 'oldest' ? 'selected' : '' ?>>Terlama</option>
            <option value="deadline" <?= $currentSort === 'deadline' ? 'selected' : '' ?>>Deadline Terdekat</option>
        </select>

        <button type="submit" class="btn">Apply</button>
    </form>

    <?php foreach ($lists as $item): ?>
        <div class="card" style="margin-bottom:12px; padding:12px;">
            <strong><?= e($item['title']) ?></strong>

            <?php if (isOverdue($item)): ?>
                <span style="background:#dc3545; color:white; padding:3px 8px; border-radius:999px; font-size:11px;">
                    OVERDUE
                </span>
            <?php endif; ?>

            <div style="font-size:13px; opacity:.7;">
                <?= e($item['description']) ?>
            </div>

            <div style="font-size:12px; opacity:.6;">
                Deadline: <?= e(formatDate($item['dead_line'])) ?>
            </div>

            <div style="margin-top:6px;">
                <?= $item['is_done'] ? '<strong style="color:green;">Selesai</strong>' : '<strong style="color:#b58100;">Belum</strong>' ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- PAGINATION -->
    <div style="margin-top:15px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?= $queryBase ?>&page=<?= $i ?>"
               style="margin-right:5px; <?= $i == $page ? 'font-weight:bold;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</section>