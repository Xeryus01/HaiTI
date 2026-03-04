# API Endpoints

Semua endpoint API berada di bawah middleware `auth:sanctum`.

## Dashboard
- `GET /api/dashboard/summary` – ringkasan statistik aset & tiket.

## Tickets
- `GET /api/tickets` – daftar tiket (admin/teknisi melihat semua, user hanya milik sendiri)
- `POST /api/tickets` – buat tiket
- `GET /api/tickets/{ticket}` – detail tiket
- `PATCH /api/tickets/{ticket}` – ubah status/assignee (admin/teknisi saja)
- `POST /api/tickets/{ticket}/status` – alias untuk update status
- `POST /api/tickets/{ticket}/comments` – tambahkan komentar

## Assets
Resource RESTful:
- `GET /api/assets`
- `POST /api/assets` – (role teknisi/admin)
- `GET /api/assets/{asset}`
- `PATCH /api/assets/{asset}`
- `DELETE /api/assets/{asset}`

## Reservations
- `GET /api/reservations`
- `POST /api/reservations`
- `GET /api/reservations/{reservation}`
- `PATCH /api/reservations/{reservation}`
- `DELETE /api/reservations/{reservation}`

## Logs
- `GET /api/logs` – hanya admin bisa lihat seluruh; user hanya his own actions.

### Authorization
- RBAC menggunakan Paket Spatie; perizinan disimpan di tabel `roles`, `permissions`, `model_has_roles`, dan `model_has_permissions`.

### Real-time
- Notifikasi broadcast via Pusher / Laravel Echo (`broadcast` driver) dikirim pada perubahan status dan komentar tiket. Client dapat mendengarkan channel `private-App.Models.User.{id}`.


Referensi lain: periksa `app/Http/Controllers/Api` untuk logika implementasi.
