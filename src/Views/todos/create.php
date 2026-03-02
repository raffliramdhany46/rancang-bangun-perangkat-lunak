<?php
declare(strict_types=1);

$errors = $errors ?? [];
$old = $old ?? [];
?>

<section class="card" style="max-width:700px; margin:auto;">
    <h2><?= e($pageHeading ?? 'Create Todo'); ?></h2>

    <form method="POST" style="display:flex; flex-direction:column; gap:14px; margin-top:16px;">

        <!-- Title -->
        <div>
            <label>Title</label><br>
            <input type="text" name="title"
                   value="<?= e($old['title'] ?? '') ?>"
                   style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;">
            <?php if(isset($errors['title'])): ?>
                <small style="color:red;"><?= e($errors['title']) ?></small>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <div>
            <label>Description</label><br>
            <textarea name="description"
                      rows="4"
                      style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"><?= e($old['description'] ?? '') ?></textarea>
            <?php if(isset($errors['description'])): ?>
                <small style="color:red;"><?= e($errors['description']) ?></small>
            <?php endif; ?>
        </div>

        <!-- Deadline -->
        <div>
            <label>Deadline</label><br>
            <input type="datetime-local"
                   name="dead_line"
                   value="<?= e($old['dead_line'] ?? '') ?>"
                   style="padding:8px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <!-- Need Achieve -->
        <div>
            <label class="checkbox-pretty">
                <input type="checkbox"
                       name="is_need_achieve"
                       value="1"
                       <?= !empty($old['is_need_achieve']) ? 'checked' : '' ?>>
                <span>Perlu Diselesaikan</span>
            </label>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn">Simpan</button>
            <a href="/dashboard.php" class="btn" style="text-decoration:none;">Batal</a>
        </div>

    </form>
</section>
