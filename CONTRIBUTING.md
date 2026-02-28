# Panduan Kontribusi

## Branch Naming
Gunakan format berikut:
- `feat/<nama-fitur>`
- `fix/<nama-perbaikan>`
- `chore/<nama-task>`
- `docs/<nama-dokumen>`

## Konvensi Commit
Format sederhana:
- `feat: tambah endpoint api todo`
- `fix: perbaiki validasi judul`
- `docs: update panduan routing`

## Workflow Git (Feature -> PR -> Review -> Merge)
1. Buat branch dari `development`.
2. Implementasi + test lokal.
3. Push branch dan buat Pull Request.
4. Lakukan code review dan revisi.
5. Merge ke `development` setelah approval.

## PR Checklist
- Perubahan sesuai scope branch.
- Tidak ada file sensitif ikut ter-commit.
- Test yang relevan dijalankan.
- Dokumentasi diperbarui jika ada perubahan behavior.
- Tidak memecah fitur existing.

## Conflict Resolution Guideline
1. `git fetch origin`
2. `git rebase origin/development` (disarankan)
3. Selesaikan conflict per file dengan fokus pada behavior terbaru.
4. Jalankan test ulang.
5. Push dengan `--force-with-lease` bila branch PR menggunakan rebase.

## Code Review Checklist
- Logic domain ada di service, bukan controller.
- Repository tidak mencampur validasi bisnis.
- Endpoint API mengembalikan format error JSON standar.
- Route baru punya method yang tepat dan konsisten.
- PHPDoc untuk class/public method sudah ada.

## Testing Expectation Sebelum PR
Minimal jalankan:
```bash
php tests/run.php
```
Jika menambah route/service baru, tambahkan test terkait.
