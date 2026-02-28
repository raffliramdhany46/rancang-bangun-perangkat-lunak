<?php
/** @var int $status */
/** @var string $message */
/** @var array<string, mixed> $details */
?>
<div class="card">
    <h1>Error <?= (int) $status; ?></h1>
    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if ($details !== []): ?>
        <div class="error">
            <strong>Detail:</strong>
            <ul>
                <?php foreach ($details as $key => $value): ?>
                    <li>
                        <strong><?= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8'); ?>:</strong>
                        <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <a class="btn btn-muted" href="/todos">Kembali ke daftar todo</a>
</div>
