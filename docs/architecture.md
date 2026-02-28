# Arsitektur Project

## Struktur Folder
- `src/`
  - `index.php`: front controller.
  - `dev-router.php`: router untuk PHP built-in server.
  - `Router.php`: core routing.
  - `Http/`: helper request/response.
  - `Controller/`: controller HTML dan API.
  - `Todo/`: repository, service, dan exception domain todo.
  - `Views/`: template HTML server-rendered.
  - `assets/`: JavaScript vanilla.
  - `config/` + `Config/`: konfigurasi aplikasi.
- `database/`: schema SQL.
- `tests/`: test native lightweight.
- `docs/`: dokumentasi teknis.

## Request Lifecycle
1. Request masuk ke `src/dev-router.php` (saat mode `php -S`).
2. Request diteruskan ke `src/index.php`.
3. `Request::fromGlobals()` membentuk object request.
4. `Router::dispatch()` mencocokkan method + path + params.
5. Controller dipanggil.
6. Controller memanggil `TodoService`.
7. `TodoService` memanggil repository (`MemoryTodoRepository` atau `PdoTodoRepository`).
8. Controller mengembalikan `Response` (HTML/JSON/redirect).
9. `Response::send()` mengirim output ke client.

## Separation of Concerns
- Router: fokus pencocokan route dan middleware.
- Controller: parsing request + panggil service + bentuk response.
- Service: validasi dan business rule.
- Repository: akses data storage.
- View: rendering HTML.
- Http helper: abstraksi request/response agar konsisten.
