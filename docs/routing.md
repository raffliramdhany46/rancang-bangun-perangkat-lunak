# Dokumentasi Routing

## Registrasi Route
Route terdaftar di `src/routes.php` menggunakan method:
- `get()`
- `post()`
- `put()`
- `patch()`
- `delete()`

Contoh:
```php
$router->get('/todos/{id}/edit', [TodoHtmlController::class, 'editForm']);
$router->patch('/api/todos/{id}', [TodoApiController::class, 'update']);
```

## Format Handler yang Didukung
Router menerima tiga bentuk handler:
- Array class string: `[TodoHtmlController::class, 'index']` (disarankan, lazy instantiation via container).
- String legacy: `'Controller\\TodoHtmlController@index'`.
- Callable object/closure: `[$controllerObject, 'index']` atau `fn (...) => ...`.

Untuk format array class string, object controller baru dibuat saat route tersebut benar-benar di-dispatch.

## Route Params
Format parameter menggunakan kurung kurawal:
- `/todos/{id}`

Router mengompilasi pattern ini ke regex dan mengirimkan params ke handler.

## Method Spoofing
Request HTML form untuk update/delete menggunakan method `POST` + field `_method`:
- `_method=PUT`
- `_method=PATCH`
- `_method=DELETE`

Parsing dilakukan di `Http\Request::resolveMethod()`.

## Middleware
Router mendukung:
- Middleware global melalui `Router::middleware()`.
- Middleware per route melalui parameter ketiga method route.

Signature middleware:
```php
function (Request $request, callable $next, array $params): Response
```

## Parameter Method Controller
Setelah handler di-resolve, router memanggil action controller dengan signature positional:
```php
$resolvedHandler($request, $params);
```

Artinya:
- `Request $request` dikirim oleh router.
- `array $params` berisi hasil parsing route parameter (`{id}`, dsb).
- Dependency class lain sebaiknya di constructor controller (di-resolve oleh container), bukan sebagai parameter method action.

## 404 dan 405
- 404: path tidak ditemukan.
- 405: path ditemukan tetapi method tidak sesuai.
  - Router menambahkan header `Allow`.

## Content Negotiation
Response JSON dipilih jika salah satu kondisi terpenuhi:
- Header `Accept` berisi `application/json`
- Query `?format=json`
- Path diawali `/api`

Selain itu response default adalah HTML.
