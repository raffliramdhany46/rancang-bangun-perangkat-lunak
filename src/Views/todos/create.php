<?php

declare(strict_types=1);
?>
<section class="card">
    <h2><?= e($pageHeading ?? 'Tambah Todo'); ?></h2>
    <form method="post" action="#">
        <div class="field">
            <label for="title">Judul Todo</label>
            <input id="title" name="title" type="text" placeholder="Contoh: Belajar OOP PHP">
        </div>
        <div class="field">
            <label for="note">Catatan</label>
            <textarea id="note" name="note" rows="5" placeholder="Tambahkan deskripsi singkat"></textarea>
        </div>
        <button type="submit">Simpan</button>
    </form>
</section>
