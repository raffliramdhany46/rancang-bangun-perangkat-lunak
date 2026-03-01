# Developer Notes

## Kesalahan Umum
- Lupa mengirim `_method` saat submit form update/delete.
- Mengubah logic bisnis di controller, bukan di service.
- Menambah endpoint tanpa update docs dan tests.

## Debugging Tips
- Aktifkan `APP_DEBUG=1` untuk detail error internal.
- Cek format response menggunakan `?format=json` saat debug route HTML.
- Gunakan `php tests/run.php` sebelum commit.

## Cara Menambah Route Baru
1. Tambahkan di `src/routes.php`.
2. Gunakan format handler `[Controller::class, 'method']` agar auto-instansiasi via container.
3. Pastikan method controller menerima `Request $request, array $params = []`.
4. Implementasikan method controller terkait.
5. Jika ada logic bisnis baru, taruh di service.
6. Tambahkan test bila behavior berubah.
7. Perbarui `docs/routing.md` atau `docs/api.md`.

Contoh:
```php
$router->get('/todos', [TodoHtmlController::class, 'index']);
```

## Catatan Parameter Action Controller
- Router memanggil action dengan urutan argumen `($request, $params)`.
- Dependency tambahan sebaiknya melalui constructor controller.
- Jangan menaruh dependency class lain langsung di parameter method action, karena belum ada method injection otomatis.

## Cara Menambah Service Baru
1. Buat class service di `src/<Domain>/`.
2. Definisikan kontrak repository jika butuh akses data.
3. Panggil service dari controller, bukan sebaliknya.
4. Tambahkan exception domain untuk error business.
5. Tambahkan test minimal validasi + happy path.

## Catatan Extend Fitur
- Gunakan pattern existing: Controller tipis -> Service -> Repository.
- Pertahankan format error JSON standar.
- Untuk kompatibilitas frontend, jangan ubah shape response `data` tanpa dokumentasi.
