# Todo Hybrid PHP Native

Project ini adalah aplikasi **Todo List hybrid** berbasis PHP native tanpa framework.
UI utama menggunakan HTML server-rendered, dan endpoint JSON tersedia di `/api/*` untuk progressive enhancement.

## Tech Stack
- PHP Native (tanpa framework)
- Router custom (`src/Router.php`)
- PDO MySQL/MariaDB (mode `sql`) atau Session Memory (mode `memory`)
- Vanilla JavaScript (`src/assets/app.js`)

## Menjalankan Project

### 1) Jalankan server (webroot `src`)
```bash
php -S localhost:8000 -t src src/dev-router.php
```

Alternatif script:
```bash
./serve.sh
```

Windows:
```powershell
.\serve.bat
```

### 2) Buka aplikasi
- `http://localhost:8000/todos`

## Setup Storage

### Mode memory (default)
Tidak perlu database.

### Mode sql (MySQL/MariaDB)
Set environment:
- `TODO_STORAGE_DRIVER=sql`
- `TODO_DB_HOST`
- `TODO_DB_PORT`
- `TODO_DB_NAME`
- `TODO_DB_USER`
- `TODO_DB_PASS`
- `TODO_DB_CHARSET` (default `utf8mb4`)

Init schema:
```sql
SOURCE database/schema.sql;
```

## Endpoint Ringkas

### HTML
- `GET /todos`
- `GET /todos/create`
- `POST /todos`
- `GET /todos/{id}/edit`
- `POST /todos/{id}` + `_method=PUT|PATCH|DELETE`
- `POST /todos/{id}/done`

### API
- `GET /api/todos`
- `GET /api/todos/{id}`
- `POST /api/todos`
- `PATCH /api/todos/{id}`
- `DELETE /api/todos/{id}`
- `POST /api/todos/{id}/done`

## Contoh API (curl)
```bash
curl -H "Accept: application/json" http://localhost:8000/api/todos
```

```bash
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title":"Belajar RBPL","description":"Kerjakan tugas"}'
```

## Struktur Folder
- `src/`: seluruh source code aplikasi (webroot)
- `database/`: schema SQL
- `tests/`: test lightweight native PHP
- `docs/`: dokumentasi teknis dan kontribusi

## Alur Fitur
1. User membuka `/todos`.
2. Router memilih controller HTML.
3. Controller memanggil `TodoService`.
4. Service memanggil repository (`sql|memory`).
5. Response dikirim sebagai HTML atau JSON sesuai content negotiation.

## Kontribusi
Ringkasan kontribusi ada di `CONTRIBUTING.md`.
Dokumentasi teknis lengkap ada di folder `docs/`.
