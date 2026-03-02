# Project Documentation

## 1. Ringkasan

Aplikasi ini adalah Todo App berbasis PHP native tanpa framework.
Routing memakai file PHP langsung (file-based routing), dengan view sederhana dan 1 layout utama.

## 2. Struktur Folder

- `src/` source code aplikasi
- `src/Views/` file tampilan
- `src/todos/` endpoint aksi todo (create/update/delete/mark done)
- `src/assets/` CSS dan asset statis
- `docs/` dokumentasi project
- `tests/` unit test PHP native

## 3. Alur Routing

### Halaman utama

- `GET /index.php` -> redirect ke `/dashboard.php`
- `GET /dashboard.php` -> dashboard ringkasan + 5 todo deadline terdekat
- `GET /about.php` -> halaman about
- `GET /lists.php` -> daftar todo lengkap + filter/sort/search/pagination
- `GET /edit.php?id={id}` -> halaman edit todo

### Endpoint todo

- `GET|POST /todos/create.php`
  - `GET`: tampilkan form create
  - `POST`: simpan todo baru, redirect ke dashboard
- `POST /todos/update.php?id={id}`
  - update todo dari form edit
- `GET|POST /todos/delete.php?id={id}[&redirect=lists]`
  - `GET`: tampilkan konfirmasi hapus
  - `POST`: hapus data lalu redirect
- `POST /todos/mark_done.php`
  - endpoint AJAX untuk menandai todo selesai dari halaman list
  - response JSON:
    - sukses: `{"success":true,...}`
    - gagal: `{"success":false,"message":"..."}`

## 4. Fitur Utama

### Dashboard

- Menampilkan jumlah task selesai.
- Menampilkan jumlah overdue.
- Menampilkan 5 task dengan deadline terdekat.
- Tersedia tombol `Lihat List Lengkap` ke `/lists.php`.

### Daftar Todo (`/lists.php`)

- Filter status: `all`, `done`, `pending`, `overdue`.
- Sorting: `latest`, `oldest`, `deadline`.
- Search berdasarkan `title`.
- Pagination (5 data per halaman).
- Aksi per item:
  - `Tandai Selesai` (AJAX JS ke `/todos/mark_done.php`)
  - `Edit` (ke `/edit.php?id=...`)
  - `Delete` (POST ke `/todos/delete.php?id=...&redirect=lists`)

### Create & Edit Todo

- Validasi minimal:
  - `title` wajib
  - `description` wajib
- Mendukung:
  - `dead_line` (`datetime-local`)
  - `is_need_achieve`
  - `is_done` (pada edit)

## 5. Komponen Teknis

- `src/bootstrap.php`
  - helper global: `config()`, `db()`, `e()`, `asset()`
- `src/View/View.php`
  - renderer view + layout
- `src/Controller/TodoHtmlController.php`
  - aksi edit/update
  - aksi AJAX `markDone()`

## 6. Database (Ringkasan Kolom)

Tabel yang dipakai: `todos`, dengan kolom yang digunakan aplikasi:

- `id`
- `title`
- `description`
- `is_done`
- `is_need_achieve`
- `dead_line`
- `created_at`
- `updated_at`

## 7. Unit Test PHP Native

Unit test ada di `tests/run.php` dan dijalankan tanpa PHPUnit.

Perintah:

```bash
php tests/run.php
```

Test yang dicakup:

- helper `e()`, `asset()`, `config()`
- render view `App\View\View::render()`
- validasi request `TodoHtmlController::markDone()`
