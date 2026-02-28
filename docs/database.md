# Dokumentasi Database

## Storage Mode
Project mendukung dua mode:
- `memory`: data todo disimpan di session.
- `sql`: data todo disimpan di MySQL/MariaDB via PDO.

## Konfigurasi SQL
Environment variable:
- `TODO_STORAGE_DRIVER=sql`
- `TODO_DB_HOST`
- `TODO_DB_PORT`
- `TODO_DB_NAME`
- `TODO_DB_USER`
- `TODO_DB_PASS`
- `TODO_DB_CHARSET`

## Schema
File schema: `database/schema.sql`

Tabel `todos`:
- `id`: primary key auto increment.
- `title`: judul todo, wajib, maksimal 120 karakter (validasi di service).
- `description`: deskripsi todo.
- `is_done`: status selesai (0/1).
- `created_at`: waktu dibuat.
- `updated_at`: waktu update terakhir.

## Inisialisasi
1. Buat database (contoh: `todo_app`).
2. Jalankan isi `database/schema.sql`.
3. Set env `TODO_STORAGE_DRIVER=sql` dan credential DB.
