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
5. Router me-resolve handler route:
   - `[Controller::class, 'method']` melalui `Container`.
   - atau callable/legacy string handler.
6. `Container` melakukan instansiasi controller + dependency constructor secara otomatis.
7. Action controller dipanggil dengan argumen `($request, $params)`.
8. Controller memanggil `TodoService`.
9. `TodoService` memanggil repository (`MemoryTodoRepository` atau `PdoTodoRepository`).
10. Controller mengembalikan `Response` (HTML/JSON/redirect).
11. `Response::send()` mengirim output ke client.

## Separation of Concerns
- Router: fokus pencocokan route dan middleware.
- Container: resolve dependency constructor untuk controller/service object.
- Controller: parsing request + panggil service + bentuk response.
- Service: validasi dan business rule.
- Repository: akses data storage.
- View: rendering HTML.
- Http helper: abstraksi request/response agar konsisten.
