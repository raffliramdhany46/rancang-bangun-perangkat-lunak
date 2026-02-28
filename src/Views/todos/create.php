<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var string $formAction */
?>
<div class="card">
    <h1>Tambah Todo</h1>
    <p class="muted">Gunakan form ini untuk menambah todo baru.</p>

    <?php if ($errors !== []): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" data-api-form="create">
        <div class="field">
            <label for="title">Judul</label>
            <input id="title" type="text" name="title" value="<?= htmlspecialchars((string) ($old['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required maxlength="120">
        </div>

        <div class="field">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4" maxlength="1000"><?= htmlspecialchars((string) ($old['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-muted" href="/todos">Kembali</a>
        </div>
    </form>
</div>
