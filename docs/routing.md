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
$router->get('/todos/{id}/edit', [$todoHtmlController, 'editForm']);
$router->patch('/api/todos/{id}', [$todoApiController, 'update']);
```

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
