# Tests

Folder ini berisi unit test sederhana berbasis PHP native (tanpa PHPUnit).

## Menjalankan test

```bash
php tests/run.php
```

## Cakupan test saat ini

- Helper global dari `src/bootstrap.php`:
  - `e()`
  - `asset()`
  - `config()`
- Render view via `App\View\View::render()`
- Validasi request pada `TodoHtmlController::markDone()`
