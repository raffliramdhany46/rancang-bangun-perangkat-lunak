# rancang-bangun-perangkat-lunak
Tugas ke 2 dari matakuliah RBPL.

## Menjalankan project

1. Windows: jalankan `serve.bat`
2. Linux/macOS/git-bash: jalankan `./serve.sh`
3. Buka `http://127.0.0.1:8080`

Server memakai PHP built-in server dengan web root di folder `src`.

## Struktur sederhana (tanpa router custom)

- `src/index.php` -> halaman home
- `src/about.php` -> halaman about
- `src/todos/create.php` -> halaman form create todo
- `src/Views/layout.php` -> layout utama
- `src/assets/` -> file statis (css/js/image)

Routing mengikuti file PHP langsung (contoh: `/about.php`).
