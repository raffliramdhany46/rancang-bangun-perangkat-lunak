<?php
/** @var array<string, string> $errors */
/** @var array<string, mixed> $todo */
?>
<div class="card">
    <h1>Edit Todo #<?= (int) $todo['id']; ?></h1>
    <p class="muted">Perubahan disimpan menggunakan method spoofing (_method=PUT).</p>

    <?php if ($errors !== []): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/todos/<?= (int) $todo['id']; ?>" data-api-form="edit" data-todo-id="<?= (int) $todo['id']; ?>">
        <input type="hidden" name="_method" value="PUT">

        <div class="field">
            <label for="title">Judul</label>
            <input id="title" type="text" name="title" value="<?= htmlspecialchars((string) $todo['title'], ENT_QUOTES, 'UTF-8'); ?>" required maxlength="120">
        </div>

        <div class="field">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4" maxlength="1000"><?= htmlspecialchars((string) $todo['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Update</button>
            <a class="btn btn-muted" href="/todos">Kembali</a>
        </div>
    </form>
</div>
