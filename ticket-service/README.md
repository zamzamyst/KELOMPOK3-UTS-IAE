# Ticket Service

Layanan ini adalah microservice untuk mengelola tiket dalam sistem pemesanan bus. Ticket Service menyediakan API untuk CRUD tiket dan halaman web admin sederhana untuk membuat, mengedit, dan menghapus tiket. Service ini terintegrasi dengan Bus Service untuk mengambil jadwal dan melakukan reservasi kursi.

- Bahasa/Framework: PHP (Laravel)
- Endpoint publik: REST API di `/api/tickets` dan halaman web di `/tickets`
- Integrasi: Bus Service (`BUS_SERVICE_URL`) untuk data `schedule` dan reservasi kursi

## Fitur Utama
- Membuat tiket baru berdasarkan `schedule_id` dengan pengecekan dan reservasi kursi ke Bus Service.
- Melihat daftar tiket, detail, update sebagian data, dan menghapus tiket.
- Web admin: form Create/Edit dengan kolom Total Harga yang terhitung otomatis saat jumlah kursi berubah.

## Prasyarat
- PHP 8.1+ (sesuai versi Laravel project)
- Composer
- Database (MySQL/MariaDB atau yang didukung Laravel) sudah terkonfigurasi di `.env`
- Bus Service berjalan dan dapat diakses (default: `http://127.0.0.1:8001`)

## Konfigurasi Lingkungan
Salin file `.env` dari contoh lalu set variabel yang dibutuhkan.

```bash
cp .env.example .env
php artisan key:generate
```

Variabel penting:
- `APP_URL` (opsional, contoh: `http://127.0.0.1:8003`)
- `BUS_SERVICE_URL` default: `http://127.0.0.1:8001`
- `DB_*` untuk koneksi database

## Instalasi
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Menjalankan Service
```bash
php artisan serve --port=8003
```

- API dasar: `http://127.0.0.1:8003/api`
- Halaman web admin: `http://127.0.0.1:8003/tickets`

Pastikan Bus Service berjalan pada `BUS_SERVICE_URL` (mis. `http://127.0.0.1:8001`).

## Model Data: Ticket
Kolom penting di tabel `tickets`:
- `id` (bigint, auto increment)
- `ticket_number` (string, unik)
- `schedule_id` (bigint)
- `passenger_name` (string)
- `passenger_contact` (string, nullable)
- `seat_count` (int)
- `total_price` (decimal 10,2)
- `status` (string) — contoh nilai: `pending`, `confirmed`, `paid`, `cancelled`, `unpaid`
- `created_at`, `updated_at`

Catatan status saat ini:
- API `store` (endpoint publik) menyetel `status` ke `confirmed` ketika tiket dibuat.
- Web admin `store` menyetel `status` ke `unpaid`.

## Alur Bisnis Singkat
1. Client mengirimkan permintaan membuat tiket (memilih `schedule_id`, isi penumpang, jumlah kursi).
2. Ticket Service memanggil Bus Service untuk mengambil detail jadwal dan memverifikasi ketersediaan kursi.
3. Jika cukup, Ticket Service meminta Bus Service melakukan reservasi kursi.
4. Tiket dibuat di database dengan `total_price = seat_count × price` dari jadwal.

## REST API

Base URL: `http://127.0.0.1:8003/api`

### Daftar Tiket
- Method: `GET`
- URL: `/tickets`
- Contoh cURL:
```bash
curl http://127.0.0.1:8003/api/tickets
```

### Detail Tiket
- Method: `GET`
- URL: `/tickets/{id}`
```bash
curl http://127.0.0.1:8003/api/tickets/1
```

### Buat Tiket
- Method: `POST`
- URL: `/tickets`
- Body JSON:
```json
{
	"schedule_id": 1,
	"passenger_name": "Budi",
	"seat_count": 2,
	"passenger_contact": "08123456789"
}
```
- Respon: `201 Created` dengan objek tiket. Jika kursi tidak cukup: `400`, jika jadwal tidak ditemukan: `404`.

### Ubah Tiket (Update)
- Method: `PUT` atau `PATCH`
- URL: `/tickets/{id}`
- Body JSON yang diizinkan via API:
```json
{
	"passenger_name": "Budi Santoso",
	"passenger_contact": "081298765432"
}
```
- Catatan: API update hanya mengizinkan perubahan `passenger_name` dan `passenger_contact`. Perhitungan harga dan seat tidak diubah lewat endpoint ini.

### Hapus Tiket
- Method: `DELETE`
- URL: `/tickets/{id}`
```bash
curl -X DELETE http://127.0.0.1:8003/api/tickets/1
```

### Endpoint Internal (untuk service ke service)
- Ubah status tiket:
	- Method: `PUT`
	- URL: `/internal/tickets/{id}/status`
	- Body JSON:
	```json
	{ "status": "paid" }
	```

## Halaman Web Admin
- Daftar tiket: `GET /tickets`
- Buat tiket: `GET /tickets/create`
	- Dropdown schedule diisi dari Bus Service (`/api/schedules`).
	- Total Harga dihitung otomatis di browser dari `price × seat_count`.
- Edit tiket: `GET /tickets/{id}/edit`
	- Dapat mengubah `passenger_name`, `passenger_contact`, dan `seat_count`.
	- Total Harga akan dihitung ulang pada saat penyimpanan berdasarkan harga schedule saat ini (atau fallback ke harga per-kursi sebelumnya jika Bus Service tidak tersedia).

## Pengujian dengan Postman
Set base URL ke `http://127.0.0.1:8003/api`.
- GET: `/tickets`
- GET: `/tickets/{id}`
- POST: `/tickets` dengan body seperti contoh di atas.
- PUT/PATCH: `/tickets/{id}` hanya untuk `passenger_name` dan `passenger_contact`.
- DELETE: `/tickets/{id}`

Contoh Update (PUT):
```
http://127.0.0.1:8003/api/tickets/1
Content-Type: application/json

{
	"passenger_name": "Nama Baru",
	"passenger_contact": "081234567890"
}
```

## Troubleshooting
- `Bus Service unreachable` atau gagal reservasi: pastikan Bus Service berjalan pada `BUS_SERVICE_URL` (default `http://127.0.0.1:8001`).
- `php artisan serve` port bentrok: ubah port mis. `--port=8004` dan sesuaikan `APP_URL` jika diperlukan.
- Validasi gagal saat membuat tiket: cek kembali `schedule_id`, jumlah kursi, serta ketersediaan kursi di Bus Service.

## Catatan Keamanan
- Endpoint internal sebaiknya dibatasi hanya antar-service (misalnya lewat gateway atau network policy).
- Validasi input sudah diterapkan pada controller; jangan mem-bypass dengan field yang tidak didukung.

## Lisensi
Bagian kode ini berada dalam repositori internal tugas/latihan. Gunakan sesuai kebutuhan proyek.
