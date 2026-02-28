# Dokumentasi API Todo

Base URL lokal:
- `http://localhost:8000`

Semua endpoint API berada di prefix `/api`.

## Format Error JSON
```json
{
  "error": {
    "message": "Pesan error",
    "details": {
      "field": "penjelasan"
    }
  }
}
```

## Endpoint

### 1) GET /api/todos
Ambil daftar todo.

Contoh:
```bash
curl -H "Accept: application/json" http://localhost:8000/api/todos
```

### 2) GET /api/todos/{id}
Ambil detail todo.

Contoh:
```bash
curl -H "Accept: application/json" http://localhost:8000/api/todos/1
```

### 3) POST /api/todos
Buat todo baru.

Contoh:
```bash
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"title":"Belajar","description":"Baca docs"}'
```

### 4) PATCH /api/todos/{id}
Update todo.

Contoh:
```bash
curl -X PATCH http://localhost:8000/api/todos/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"title":"Belajar API","description":"Update via patch"}'
```

### 5) DELETE /api/todos/{id}
Hapus todo.

Contoh:
```bash
curl -X DELETE -H "Accept: application/json" http://localhost:8000/api/todos/1
```

### 6) POST /api/todos/{id}/done
Tandai todo selesai.

Contoh:
```bash
curl -X POST -H "Accept: application/json" http://localhost:8000/api/todos/1/done
```

## Catatan Negotiation
Selain endpoint `/api`, endpoint HTML juga bisa menghasilkan JSON jika:
- `Accept: application/json`, atau
- query `?format=json`.
