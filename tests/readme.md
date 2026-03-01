# Test Lightweight

Project ini menggunakan test runner native PHP tanpa dependency eksternal.

Jalankan:
```bash
php tests/run.php
```

Cakupan minimal saat ini:
- Container: auto-resolve dependency constructor, cache instance, dan error class tidak ditemukan.
- Router: params, 404, 405.
- Router handler: dukungan `[Controller::class, 'method']`, `Class@method`, dan object callable.
- TodoService: validasi dan not-found.
