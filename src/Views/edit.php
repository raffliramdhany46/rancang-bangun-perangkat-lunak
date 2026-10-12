<?php

declare(strict_types=1);

$todo = $todo ?? [];

function toLocalDatetime(?string $ts): string {
    // convert "YYYY-mm-dd HH:ii:ss" => "YYYY-mm-ddTHH:ii" untuk input datetime-local
    if (!$ts) return '';
    try {
        $dt = new DateTime($ts);
        return $dt->format('Y-m-d\TH:i');
    } catch (Throwable $e) {
        return '';
    }
}
?>

<section class="card">
    <h2><?= e($pageHeading ?? 'Edit Todo'); ?></h2>
    <p style="opacity:.85; margin-top:6px;">
        Edit data todo yang dipilih.
    </p>

    <hr style="margin:16px 0; opacity:.25;">

    <form method="POST" action="/todos/update.php?id=<?= e((string)($todo['id'] ?? '0')) ?>">
        <div style="display:grid; gap:10px; max-width:680px;">

            <label>
                <div style="font-weight:600; margin-bottom:6px;">Title</div>
                <input
                    type="text"
                    name="title"
                    value="<?= e((string)($todo['title'] ?? '')) ?>"
                    maxlength="120"
                    required
                    style="width:100%; padding:10px; border-radius:10px; border:1px solid rgba(0,0,0,.15);"
                >
            </label>

            <label>
                <div style="font-weight:600; margin-bottom:6px;">Description</div>
                <textarea
                    name="description"
                    required
                    rows="5"
                    style="width:100%; padding:10px; border-radius:10px; border:1px solid rgba(0,0,0,.15);"
                ><?= e((string)($todo['description'] ?? '')) ?></textarea>
            </label>

            <label>
                <div style="font-weight:600; margin-bottom:6px;">Deadline</div>
                <input
                    type="datetime-local"
                    name="dead_line"
                    value="<?= e(toLocalDatetime($todo['dead_line'] ?? null)) ?>"
                    style="padding:10px; border-radius:10px; border:1px solid rgba(0,0,0,.15);"
                >
                <div style="font-size:12px; opacity:.7; margin-top:4px;">
                    Kosongkan kalau tidak pakai deadline.
                </div>
            </label>

           <div style="display:grid; gap:14px; max-width:680px;">

    <!-- Status Selesai -->
    <div style="padding:12px; border:1px solid rgba(0,0,0,.08); border-radius:12px;">
        <div style="font-weight:600; margin-bottom:8px;">Status Penyelesaian</div>

        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
            <input
                type="checkbox"
                name="is_done"
                value="1"
                <?= ((int)($todo['is_done'] ?? 0) === 1) ? 'checked' : '' ?>
                style="width:18px; height:18px;"
            >
            <span>
                Tandai sebagai <strong>Selesai</strong>
            </span>
        </label>

        <div style="margin-top:8px;">
            <?php if ((int)($todo['is_done'] ?? 0) === 1): ?>
                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#e6f7ee; color:#198754; font-weight:600;">
                    ✔ Sudah Selesai
                </span>
            <?php else: ?>
                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#fff3cd; color:#b58100; font-weight:600;">
                    ⏳ Belum Selesai
                </span>
            <?php endif; ?>
        </div>
    </div>


    <!-- Status Perlu Diselesaikan -->
    <div style="padding:12px; border:1px solid rgba(0,0,0,.08); border-radius:12px;">
        <div style="font-weight:600; margin-bottom:8px;">Prioritas Task</div>

        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
            <input
                type="checkbox"
                name="is_need_achieve"
                value="1"
                <?= ((int)($todo['is_need_achieve'] ?? 0) === 1) ? 'checked' : '' ?>
                style="width:18px; height:18px;"
            >
            <span>
                Task ini <strong>Perlu Diselesaikan</strong>
            </span>
        </label>

        <div style="margin-top:8px;">
            <?php if ((int)($todo['is_need_achieve'] ?? 0) === 1): ?>
                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#e7f1ff; color:#0d6efd; font-weight:600;">
                    🔥 Wajib Dikerjakan
                </span>
            <?php else: ?>
                <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:#f8f9fa; color:#6c757d; font-weight:600;">
                    Tidak Mendesak
                </span>
            <?php endif; ?>
        </div>
    </div>

</div>
            <div style="display:flex; gap:10px; margin-top:6px;">
                <button type="submit" class="btn">Save Changes</button>
                <a class="btn" href="/dashboard.php" style="text-decoration:none;">Cancel</a>
            </div>

        </div>
    </form>
</section>
