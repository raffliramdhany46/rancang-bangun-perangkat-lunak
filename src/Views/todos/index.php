<?php
/** @var array<int, array<string, mixed>> $todos */
?>
<div class="card">
    <h1>Todo List</h1>
    <p class="muted">Aplikasi hybrid: HTML server-rendered + endpoint JSON API.</p>
    <div class="actions">
        <a class="btn btn-primary" href="/todos/create">Tambah Todo</a>
        <a class="btn btn-muted" href="/todos?format=json">Lihat JSON (Negotiation)</a>
    </div>
</div>

<div id="todo-list" data-enhance="true"></div>

<?php foreach ($todos as $todo): ?>
    <div class="card" data-todo-id="<?= (int) $todo['id']; ?>">
        <h3 class="<?= (int) $todo['is_done'] === 1 ? 'done' : ''; ?>">
            #<?= (int) $todo['id']; ?> - <?= htmlspecialchars((string) $todo['title'], ENT_QUOTES, 'UTF-8'); ?>
        </h3>
        <p><?= nl2br(htmlspecialchars((string) $todo['description'], ENT_QUOTES, 'UTF-8')); ?></p>
        <p class="muted">Updated: <?= htmlspecialchars((string) $todo['updated_at'], ENT_QUOTES, 'UTF-8'); ?></p>

        <div class="actions">
            <a class="btn btn-muted" href="/todos/<?= (int) $todo['id']; ?>/edit">Edit</a>

            <?php if ((int) $todo['is_done'] === 0): ?>
                <form method="POST" action="/todos/<?= (int) $todo['id']; ?>/done">
                    <button type="submit" class="btn btn-primary">Tandai Selesai</button>
                </form>
            <?php endif; ?>

            <form method="POST" action="/todos/<?= (int) $todo['id']; ?>" onsubmit="return confirm('Hapus todo ini?');">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($todos === []): ?>
    <div class="card">
        <p>Belum ada todo. Klik <strong>Tambah Todo</strong> untuk mulai.</p>
    </div>
<?php endif; ?>
