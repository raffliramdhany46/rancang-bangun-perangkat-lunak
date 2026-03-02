<?php
declare(strict_types=1);

$doneCount = (int)($doneCount ?? 0);
$overdueCount = (int)($overdueCount ?? 0);
$upcomingTodos = $upcomingTodos ?? [];

function fmtDeadline(?string $ts): string {
    if (!$ts) return '-';
    try {
        $dt = new DateTime($ts);
        return $dt->format('d M Y, H:i');
    } catch (Throwable $e) {
        return $ts;
    }
}

function isOverdue(?string $ts): bool {
    if (!$ts) return false;
    try {
        return (new DateTime($ts)) < new DateTime('now');
    } catch (Throwable $e) {
        return false;
    }
}
?>

<section class="card">
    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h2 style="margin:0 0 6px;"><?= e($pageHeading ?? 'Dashboard'); ?></h2>
            <p style="margin:0; opacity:.85;">
                Ringkasan tugas + 5 deadline terdekat. Biar kelihatan mana yang harus kamu gas, mana yang udah telat.
            </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="btn" href="/todos/create.php" style="text-decoration:none;">+ Create Todo</a>
        </div>
    </div>

    <hr style="margin:16px 0; opacity:.25;">

    <!-- Summary cards -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
        <div class="card" style="padding:14px;">
            <div style="font-size:12px; opacity:.75;">Task Selesai</div>
            <div style="font-size:28px; font-weight:700; line-height:1.1; margin-top:6px;"><?= e((string)$doneCount) ?></div>
            <div style="font-size:12px; opacity:.75; margin-top:6px;">Total yang sudah ditandai selesai</div>
        </div>

        <div class="card" style="padding:14px;">
            <div style="font-size:12px; opacity:.75;">Task Kelewat Deadline</div>
            <div style="font-size:28px; font-weight:700; line-height:1.1; margin-top:6px;"><?= e((string)$overdueCount) ?></div>
            <div style="font-size:12px; opacity:.75; margin-top:6px;">Deadline lewat & belum selesai</div>
        </div>

        <div class="card" style="padding:14px;">
            <div style="font-size:12px; opacity:.75;">Deadline Terdekat</div>
            <div style="font-size:28px; font-weight:700; line-height:1.1; margin-top:6px;"><?= e((string)count($upcomingTodos)) ?></div>
            <div style="font-size:12px; opacity:.75; margin-top:6px;">Dari 5 yang ditampilkan</div>
        </div>
    </div>

    <h3 style="margin:18px 0 10px;">5 Task Teratas yang Mau Deadline</h3>

    <?php if (empty($upcomingTodos)): ?>
        <div class="card" style="padding:14px; opacity:.85;">
            Tidak ada task upcoming (yang belum selesai dan punya deadline).
        </div>
    <?php else: ?>
        <div class="card" style="padding:0; overflow:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:760px;">
                <thead>
                <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,.08);">
                    <th style="padding:12px;">Title</th>
                    <th style="padding:12px;">Deadline</th>
                    <th style="padding:12px;">Perlu Diselesaikan?</th>
                    <th style="padding:12px;">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($upcomingTodos as $todo): ?>
                    <?php
                        $deadline = $todo['dead_line'] ?? null;
                        $over = isOverdue($deadline);
                        $need = (int)($todo['is_need_achieve'] ?? 0) === 1;
                    ?>
                    <tr style="border-bottom:1px solid rgba(0,0,0,.06);">
                        <td style="padding:12px;">
                            <div style="font-weight:600;"><?= e((string)($todo['title'] ?? 'No Title')) ?></div>
                            <?php if (!empty($todo['description'])): ?>
                                <div style="font-size:12px; opacity:.75; margin-top:4px;">
                                    <?= e(mb_strimwidth((string)$todo['description'], 0, 120, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding:12px;">
                            <div><?= e(fmtDeadline($deadline)) ?></div>
                            <?php if ($over): ?>
                                <div style="font-size:12px; margin-top:4px;">
                                    <span style="display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(220, 53, 69, .12); color:#dc3545; font-weight:600;">
                                        OVERDUE
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="padding:12px;">
                            <?php if ($need): ?>
                                <span style="display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(13,110,253,.12); color:#0d6efd; font-weight:600;">
                                    Ya (Need Achieve)
                                </span>
                            <?php else: ?>
                                <span style="display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(108,117,125,.12); color:#6c757d; font-weight:600;">
                                    Tidak
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:12px;">
                            <span style="display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(255,193,7,.14); color:#b58100; font-weight:700;">
                                BELUM SELESAI
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>