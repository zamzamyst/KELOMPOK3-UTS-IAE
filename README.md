# KELOMPOK3-UTS-IAE - Sistem Manajemen Bus Microservices

Proyek UTS IAE/EAI Kelompok 3 - Implementasi arsitektur microservices untuk sistem manajemen bus dan tiket menggunakan Node.js, Laravel, dengan integrasi API Gateway yang lengkap.

## 📋 Daftar Isi

- [Latar Belakang](#latar-belakang)
- [Tujuan Proyek](#tujuan-proyek)
- [Deskripsi Proyek](#deskripsi-proyek)
- [Spesifikasi Teknis](#spesifikasi-teknis)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Anggota Kelompok](#anggota-kelompok)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Dokumentasi API](#dokumentasi-api)
- [Struktur Proyek](#struktur-proyek)
- [Contoh Request & Response](#contoh-request--response)
- [Testing & Validation](#testing--validation)
- [Performance Tips](#performance-tips)
- [Security Best Practices](#security-best-practices)
- [Troubleshooting](#troubleshooting)

## 🎯 Latar Belakang

Di era digital saat ini, banyak aplikasi tidak lagi berdiri sendiri, melainkan saling terhubung melalui Application Programming Interface (API). API memungkinkan sistem berbeda untuk bertukar data dan fungsi. Untuk memastikan komunikasi antar sistem berjalan baik dan terdokumentasi dengan jelas, proyek ini mengimplementasikan microservices architecture dengan API Gateway sebagai pintu masuk komunikasi.

## 🎓 Tujuan Proyek

Proyek ini bertujuan untuk:

1. **Membangun Microservices**: Membuat minimal 4 layanan (sesuai jumlah anggota kelompok) yang dapat berkomunikasi melalui API
2. **Service as Provider**: Menyediakan API endpoint untuk CRUD operations (GET, POST, PUT, DELETE)
3. **Service as Consumer**: Implementasi komunikasi antar service untuk integrasi data
4. **API Gateway**: Mengimplementasikan 1 API Gateway sebagai pintu masuk komunikasi
5. **REST API Format**: Menggunakan format JSON untuk semua komunikasi
6. **Dokumentasi API**: Membuat dokumentasi lengkap menggunakan Postman
7. **Service Integration**: Memahami konsep integrasi service dan data flow antar sistem

## 📝 Deskripsi Proyek

Sistem manajemen bus terpadu yang dibangun dengan arsitektur microservices. Setiap service memiliki tanggung jawab spesifik dan dapat berkomunikasi dengan service lain melalui API Gateway.

### Layanan Utama:

1. **Bus Service** - Manajemen data bus dan rute perjalanan
   - CRUD untuk data bus
   - Manajemen rute perjalanan
   
2. **Ticket Service** - Manajemen tiket dan reservasi penumpang
   - Pemesanan tiket
   - Manajemen reservasi
   - Laporan tiket terjual
   
3. **Payment Service** - Manajemen transaksi pembayaran
   - Proses pembayaran
   - Update status pembayaran
   - History transaksi
   
4. **Tracking Service** - Manajemen tracking/pelacakan perjalanan bus
   - Real-time tracking lokasi
   - Status perjalanan
   - History perjalanan

## 📊 Spesifikasi Teknis

| Aspek | Deskripsi |
|-------|-----------|
| **Arsitektur** | Microservices dengan 4 layanan REST API yang saling berkomunikasi |
| **Format Data** | JSON |
| **Dokumentasi** | Postman Collection (.json) |
| **Framework Backend** | Laravel (PHP 8.2+) dan Node.js/Express |
| **Database** | SQLite / MySQL (setiap service punya database terpisah) |
| **API Gateway** | Node.js/Express |
| **Frontend** | PHP Server dengan HTML/JavaScript |
| **Testing Tools** | Postman |
| **Version Control** | Git |

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                 Frontend Dashboard                  │
│              (Port 8000, PHP Server)                │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────▼──────────────────────────────┐
│              API Gateway                            │
│         (Port 4000, Node.js/Express)               │
└──────────────────────┬──────────────────────────────┘
                       │
    ┌──────────────────▼──────────────────┐
    │      Bus Service (Laravel)          │
    │         Port 8001                   │
    └──┬──────────────────────────────────┬┘
       │                                  │
    ┌──▼──────────────────┐         ┌─────▼──────────────┐
    │ Tracking Service    │         │  Ticket Service    │
    │ (Laravel)           │         │  (Laravel)         │
    │ Port 8004           │         │  Port 8003         │
    ├─────────────────────┤         ├────────────────────┤
    │  DB: tracking_db    │         │  DB: ticket_db     │
    └─────────────────────┘         └─────────┬──────────┘
         ↑                                    │
         │                         ┌──────────▼────────┐
    ┌────┴──────────┐              │ Payment Service  │
    │ Bus Service   │              │  (Laravel)       │
    │  DB: bus_db   │              │  Port 8002       │
    └───────────────┘              ├──────────────────┤
                                   │  DB: payment_db  │
                                   └──────────────────┘
```

**Flow Koneksi:** Tracking ← Bus → Ticket → Payment  
**Prinsip:** Setiap service memiliki database terpisah untuk menjaga microservices independence

### API Gateway Routes:

```
GET    /health                        # Health check semua services
GET    /bus/*                         # Proxy ke Bus Service
GET    /ticket/*                      # Proxy ke Ticket Service
GET    /payment/*                     # Proxy ke Payment Service
GET    /tracking/*                    # Proxy ke Tracking Service
```

## 👥 Anggota Kelompok

| No | Nama | Peran |
|----|------|-------|
| 1  | [Nama] | Leader / Bus Service |
| 2  | [Nama] | Ticket Service |
| 3  | [Nama] | Payment Service |
| 4  | [Nama] | Tracking Service / API Gateway |

*Catatan: Silakan update dengan nama anggota kelompok Anda*

## 📦 Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:

### Required:
- **Git** - Version control system
  - Download: [https://git-scm.com/](https://git-scm.com/)
  
- **PHP 8.2+** - Untuk Laravel services
  - Download: [https://www.php.net/](https://www.php.net/)
  - Alternatif: [XAMPP](https://www.apachefriends.org/) atau [Laragon](https://laragon.org/)
  
- **Composer** - Dependency manager untuk PHP
  - Download: [https://getcomposer.org/](https://getcomposer.org/)
  - Pastikan dapat diakses dari terminal: `composer --version`
  
- **Node.js 16+** - Runtime JavaScript
  - Download: [https://nodejs.org/](https://nodejs.org/)
  - Verifikasi: `node --version` dan `npm --version`

### Optional:
- **MySQL/MariaDB** - Database (jika menggunakan database lokal)
- **Postman** - Untuk testing API (ada file `KELOMPOK3-UTS-IAE.postman_collection.json`)

## 🚀 Instalasi

### Step 1: Clone Repository

```bash
# Navigate ke direktori yang diinginkan
cd c:\path\to\projects

# Clone repository
git clone https://github.com/zamzamyst/KELOMPOK3-UTS-IAE.git

# Masuk ke direktori proyek
cd KELOMPOK3-UTS-IAE
```

### Step 2: Instalasi Dependencies - API Gateway

```bash
cd api-gateway

# Install dependencies Node.js
npm install

# Kembali ke root
cd ..
```

### Step 3: Instalasi Dependencies - Bus Service

```bash
cd bus-service

# Install dependencies PHP (Composer)
composer install

# Install dependencies Node.js (untuk Vite)
npm install

# Kembali ke root
cd ..
```

### Step 4: Instalasi Dependencies - Payment Service

```bash
cd payment-service

# Install dependencies PHP
composer install

# Install dependencies Node.js
npm install

# Kembali ke root
cd ..
```

### Step 5: Instalasi Dependencies - Ticket Service

```bash
cd ticket-service

# Install dependencies PHP
composer install

# Install dependencies Node.js
npm install

# Kembali ke root
cd ..
```

### Step 6: Instalasi Dependencies - Tracking Service

```bash
cd tracking-service

# Install dependencies PHP
composer install

# Install dependencies Node.js
npm install

# Kembali ke root
cd ..
```

## ⚙️ Konfigurasi

### Konfigurasi Environment Files

Setiap service Laravel memerlukan file `.env`. Ikuti langkah berikut:

#### Bus Service
```bash
cd bus-service

# Copy file environment
cp .env.example .env

# Generate aplikasi key
php artisan key:generate

# Kembali ke root
cd ..
```

Lakukan hal yang sama untuk:
- `payment-service`
- `ticket-service`
- `tracking-service`

Contoh konfigurasi `.env`:
```env
APP_NAME=BusService
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001

DB_CONNECTION=sqlite
# atau jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bus_service
# DB_USERNAME=root
# DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Konfigurasi Frontend

Edit file `frontend/config.php`:

```php
<?php
return [
    'api_gateway_url' => 'http://localhost:4000',
    'bus_service_url' => 'http://localhost:8001',
    'ticket_service_url' => 'http://localhost:8003',
    'payment_service_url' => 'http://localhost:8002',
    'tracking_service_url' => 'http://localhost:8004',
];
```

## 🎮 Menjalankan Aplikasi

### Option 1: Menjalankan Semua Services Secara Manual

Buka terminal terpisah untuk setiap service:

#### Terminal 1: API Gateway
```bash
cd api-gateway
npm start
# Output: API Gateway running on port 4000
```

#### Terminal 2: Bus Service
```bash
cd bus-service

# Setup awal (hanya perlu dilakukan sekali)
php artisan migrate
php artisan seed --class=BusSeeder

# Jalankan development server
php artisan serve --port=8001
```

#### Terminal 3: Ticket Service
```bash
cd ticket-service
php artisan migrate
php artisan serve --port=8003
```

#### Terminal 4: Payment Service
```bash
cd payment-service
php artisan migrate
php artisan serve --port=8002
```

#### Terminal 5: Tracking Service
```bash
cd tracking-service
php artisan migrate
php artisan serve --port=8004
```

#### Terminal 6: Frontend Dashboard
```bash
cd frontend
php -S 127.0.0.1:8000 -t .
# Akses: http://localhost:8000
```

### Option 2: Menjalankan Bus Service dengan Development Mode Lengkap

Bus Service memiliki setup lengkap dengan Vite, queue, dan logging:

```bash
cd bus-service

# Jalankan semua dengan concurrently
npm run dev

# Ini akan menjalankan:
# - Laravel server (port 8001)
# - Queue listener
# - Pail logs
# - Vite dev server
```

### Option 3: Menggunakan Laragon (Windows)

Jika menggunakan Laragon, Anda dapat menjalankan beberapa service langsung:

1. Copy setiap service ke folder `www` Laragon
2. Setup virtual hosts di Laragon
3. Jalankan dari Laragon GUI

## 📁 Struktur Folder

```
KELOMPOK3-UTS-IAE/
├── README.md                                    # Dokumentasi ini
├── KELOMPOK3-UTS-IAE.postman_collection.json   # Postman API collection
├── api-gateway/                                 # API Gateway (Node.js)
│   ├── index.js                                 # Entry point
│   ├── package.json                             # Node.js dependencies
│   └── node_modules/
├── bus-service/                                 # Bus Service (Laravel)
│   ├── artisan                                  # Artisan CLI
│   ├── app/
│   │   ├── Http/                               # Controllers, Middleware
│   │   ├── Models/                             # Eloquent Models
│   │   └── Providers/                          # Service Providers
│   ├── config/                                 # Konfigurasi aplikasi
│   ├── database/
│   │   ├── migrations/                         # Database migrations
│   │   ├── seeders/                            # Database seeders
│   │   └── factories/                          # Model factories
│   ├── routes/                                 # Route definitions
│   │   ├── api.php                             # API routes
│   │   ├── web.php                             # Web routes
│   │   └── console.php                         # Console routes
│   ├── resources/
│   │   ├── css/                                # CSS files
│   │   ├── js/                                 # JavaScript files
│   │   └── views/                              # Blade templates
│   ├── public/
│   │   └── index.php                           # Entry point publik
│   ├── storage/                                # Cache, logs, uploads
│   ├── tests/                                  # Unit & Feature tests
│   ├── composer.json                           # PHP dependencies
│   ├── package.json                            # Node.js dependencies
│   ├── vite.config.js                          # Vite configuration
│   ├── phpunit.xml                             # PHPUnit config
│   └── .env                                    # Environment variables
├── ticket-service/                             # Ticket Service (Laravel)
│   └── [Struktur sama dengan bus-service]
├── payment-service/                            # Payment Service (Laravel)
│   └── [Struktur sama dengan bus-service]
├── tracking-service/                           # Tracking Service (Laravel)
│   └── [Struktur sama dengan bus-service]
└── frontend/                                   # Frontend Dashboard (PHP)
    ├── index.html                              # Main HTML
    ├── index.php                               # Router
    ├── config.php                              # Configuration
    └── README.md
```

## 🔌 Dokumentasi API

### Menggunakan Postman

Proyek ini menyediakan Postman Collection yang dapat digunakan untuk testing API:

1. Buka Postman
2. Click `Import`
3. Pilih file `KELOMPOK3-UTS-IAE.postman_collection.json`
4. Pastikan semua services sudah berjalan
5. Mulai test API endpoints

### API Gateway Endpoints

**Base URL:** `http://localhost:4000`

#### Proxy Endpoints:
```
POST   /bus/*              → Bus Service (8001)
POST   /ticket/*           → Ticket Service (8002)
POST   /payment/*          → Payment Service (8003)
POST   /tracking/*         → Tracking Service (8004)
```

### Bus Service Endpoints

**Base URL:** `http://localhost:8001`

```
GET    /api/buses          # Ambil semua bus
GET    /api/buses/{id}     # Ambil detail bus
POST   /api/buses          # Buat bus baru
PUT    /api/buses/{id}     # Update bus
DELETE /api/buses/{id}     # Hapus bus
```

### Ticket Service Endpoints

**Base URL:** `http://localhost:8003`

```
GET    /api/tickets        # Ambil semua tiket
POST   /api/tickets        # Buat tiket baru
GET    /api/tickets/{id}   # Ambil detail tiket
PUT    /api/tickets/{id}   # Update tiket
DELETE /api/tickets/{id}   # Hapus tiket
```

### Payment Service Endpoints

**Base URL:** `http://localhost:8002`

```
GET    /api/payments       # Ambil semua pembayaran
POST   /api/payments       # Proses pembayaran
GET    /api/payments/{id}  # Ambil detail pembayaran
```

### Tracking Service Endpoints

**Base URL:** `http://localhost:8004`

```
GET    /api/tracking       # Ambil semua tracking
POST   /api/tracking       # Buat tracking baru
GET    /api/tracking/{id}  # Ambil detail tracking
```

## 📋 Contoh Request & Response

### Contoh: GET /api/buses (via API Gateway)

**Request:**
```http
GET http://localhost:4000/bus/api/buses
Authorization: Bearer <token>
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Bus A1",
      "capacity": 40,
      "license_plate": "B 1234 AB",
      "status": "active",
      "created_at": "2024-11-13T10:00:00Z"
    }
  ]
}
```

### Contoh: POST /api/buses (Membuat Bus Baru)

**Request:**
```http
POST http://localhost:4000/bus/api/buses
Content-Type: application/json
Authorization: Bearer <token>

{
  "name": "Bus A2",
  "capacity": 45,
  "license_plate": "B 5678 CD",
  "status": "active"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "name": "Bus A2",
    "capacity": 45,
    "license_plate": "B 5678 CD",
    "status": "active",
    "created_at": "2024-11-13T11:00:00Z"
  }
}
```

### Contoh: GET /api/tickets (Ambil Tiket)

**Request:**
```http
GET http://localhost:4000/ticket/api/tickets?bus_id=1&limit=10&offset=0
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "bus_id": 1,
      "seat_number": "A1",
      "passenger_name": "John Doe",
      "status": "booked",
      "price": 100000,
      "created_at": "2024-11-13T10:00:00Z"
    }
  ],
  "pagination": {
    "total": 40,
    "limit": 10,
    "offset": 0
  }
}
```

### Contoh: POST /api/payments (Proses Pembayaran)

**Request:**
```http
POST http://localhost:4000/payment/api/payments
Content-Type: application/json

{
  "ticket_id": 1,
  "amount": 100000,
  "payment_method": "transfer",
  "reference_number": "TRF123456"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ticket_id": 1,
    "amount": 100000,
    "status": "completed",
    "payment_method": "transfer",
    "reference_number": "TRF123456",
    "created_at": "2024-11-13T11:00:00Z"
  }
}
```

## 🧪 Testing & Validation

### Unit Tests

```bash
cd bus-service
php artisan test
```

### API Integration Tests dengan Postman

1. **Import Postman Collection**
   - Buka Postman
   - Click `Import`
   - Pilih file `KELOMPOK3-UTS-IAE.postman_collection.json`

2. **Setup Environment Variables**
   ```json
   {
     "api_gateway_url": "http://localhost:4000",
     "bus_service_url": "http://localhost:8001",
     "ticket_service_url": "http://localhost:8003",
     "payment_service_url": "http://localhost:8002",
     "tracking_service_url": "http://localhost:8004"
   }
   ```

3. **Jalankan Test Collections**
   - Klik `Run` pada collection
   - Perhatikan response status dan data

### Health Check

Verifikasi semua services berjalan dengan baik:

```bash
# Cek API Gateway
curl http://localhost:4000/health

# Cek masing-masing service
curl http://localhost:8001/health
curl http://localhost:8003/health
curl http://localhost:8002/health
curl http://localhost:8004/health
```

## 📊 Performance Tips

1. **Caching:** Implementasikan caching untuk mengurangi database query
2. **Pagination:** Gunakan pagination untuk data yang besar
3. **Indexing:** Tambahkan index pada kolom yang sering di-query
4. **Rate Limiting:** Implementasikan rate limiting di API Gateway
5. **Logging:** Monitor logs untuk memastikan tidak ada bottleneck
6. **Database Connection Pooling:** Gunakan connection pooling untuk database

## 🔒 Security Best Practices

1. **Input Validation:** Selalu validasi input data dari user
2. **HTTPS:** Gunakan HTTPS di production (bukan HTTP)
3. **Authentication:** Implementasikan JWT untuk secure communication
4. **Authorization:** Implement role-based access control (RBAC)
5. **Environment Variables:** Jangan expose sensitive information
6. **CORS:** Konfigurasi CORS dengan benar
7. **SQL Injection Prevention:** Gunakan parameterized queries
8. **Rate Limiting:** Lindungi API dari brute force attacks

## 🛠️ Troubleshooting

### Error: "PHP command not found"
**Solusi:**
- Pastikan PHP sudah di-install: `php --version`
- Tambahkan PHP ke sistem PATH
- Windows: Tambahkan `C:\php` (atau path instalasi) ke Environment Variables

### Error: "Composer command not found"
**Solusi:**
- Install Composer dari https://getcomposer.org/
- Pastikan dapat diakses: `composer --version`

### Error: "npm ERR! code ERESOLVE"
**Solusi:**
```bash
npm install --legacy-peer-deps
```

### Error: "Port 4000 already in use"
**Solusi:**
```bash
# Windows - cari proses yang menggunakan port
netstat -ano | findstr :4000

# Kemudian kill proses tersebut
taskkill /PID <PID> /F

# Atau gunakan port lain
node index.js --port 4001
```

### Error: "Database connection refused"
**Solusi:**
1. Pastikan MySQL/SQLite sudah berjalan
2. Cek konfigurasi di file `.env`
3. Jalankan migration: `php artisan migrate`

### Service tidak bisa berkomunikasi antar service
**Solusi:**
1. Pastikan semua service sudah running
2. Cek URL di API Gateway configuration
3. Pastikan tidak ada firewall yang memblokir

### Error: "SQLSTATE[HY000]: General error: 1 Can't create table"
**Solusi:**
```bash
# Bersihkan dan buat ulang database
php artisan migrate:fresh
php artisan db:seed
```

## 📞 Support & Kontribusi

Untuk pertanyaan atau masalah, silakan:
1. Cek dokumentasi yang ada
2. Lihat file error log di `storage/logs/`
3. Buat issue di repository

## 📝 Catatan Pengembang

- Pastikan menggunakan PHP 8.2+ untuk kompatibilitas Laravel 12
- Gunakan Node.js LTS untuk stabilitas
- Jangan commit file `.env` - gunakan `.env.example`
- Pastikan environment variables sudah benar sebelum deploy
- Update dokumentasi API setiap kali ada perubahan endpoint
- Buat commit message yang jelas dan deskriptif

## 📅 Timeline Project

| Tahap | Waktu | Deskripsi |
|-------|-------|-----------|
| Planning & Setup | Week 1 | Persiapan environment dan repository |
| Development | Week 1-2 | Implementasi service dan API |
| Testing | Week 2 | Testing API dan integration |
| Documentation | Week 2 | Finalisasi dokumentasi |
| Presentation | UTS Week | Demo dan presentasi |

## 📖 Dokumentasi Tambahan

- Lihat `frontend/README.md` untuk dokumentasi frontend
- Lihat `api-gateway/package.json` untuk dependency API Gateway
- Lihat masing-masing service `composer.json` untuk PHP dependencies

## 🙏 Referensi

Project ini mengikuti standar dan best practices dari:
- [REST API Design Best Practices](https://restfulapi.net/)
- [Microservices Patterns](https://microservices.io/patterns/)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Laravel Documentation](https://laravel.com/docs/)
- [Express.js Guide](https://expressjs.com/)

## 📄 Lisensi

MIT License - Lihat LICENSE file untuk detail lebih lanjut

---

**Last Updated:** November 2024  
**Project Team:** KELOMPOK3-UTS-IAE  
**Repository:** https://github.com/zamzamyst/KELOMPOK3-UTS-IAE  
**Status:** In Development ✓
