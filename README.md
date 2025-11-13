# BusTrack Hub - Sistem Manajemen Bus Microservices

**Proyek UTS IAE/EAI Kelompok 3**

Sistem manajemen transportasi bus terpadu yang menghubungkan layanan bus, tiket, pembayaran, dan tracking dalam satu ekosistem microservices menggunakan REST API.

## 📋 Daftar Isi

- [Deskripsi Singkat](#deskripsi-singkat)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Anggota & Peran](#anggota--peran)
- [Prasyarat](#prasyarat)
- [Panduan Menjalankan](#panduan-menjalankan)
- [Environment Variables](#environment-variables)
- [API Endpoints Ringkas](#api-endpoints-ringkas)
- [Struktur Proyek](#struktur-proyek)
- [Testing & Troubleshooting](#testing--troubleshooting)

---

## 🎯 Deskripsi Singkat

**BusTrack Hub** adalah platform manajemen transportasi bus berbasis microservices yang mengintegrasikan:

- 🚌 **Bus Service**: Manajemen data bus dan rute perjalanan
- 🎫 **Ticket Service**: Pemesanan tiket dan manajemen reservasi
- 💳 **Payment Service**: Proses pembayaran dan transaksi
- 📍 **Tracking Service**: Pelacakan real-time dan status perjalanan

Semua service berkomunikasi melalui **API Gateway** yang berfungsi sebagai pintu masuk tunggal, dan setiap service memiliki database independen sesuai prinsip microservices.

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                     CLIENT / FRONTEND                           │
│              (Port 8000 - PHP Server)                           │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                    HTTP/REST (JSON)
                               │
┌──────────────────────────────▼──────────────────────────────────┐
│                      API GATEWAY                                │
│                   (Port 4000)                                   │
│              Node.js/Express                                    │
└──────────────────────────────┬──────────────────────────────────┘
                               │
                    ┌──────────┴──────────┐
                    │                     │
            ┌───────▼───────┐             │
            │   BUS SERVICE │             │
            │   (Port 8001) │─────────────+──────────┐   
            └───┬───────┬───┘             │          │   
                │       │                 │          │
         ┌──────▼┐   ┌──▼─────────────┐   │          │
         │       │   │                │   │          │
    ┌────▼───┐ ┌─▼───▼─────┐    ┌─────▼───▼──┐       │
    │TRACKING│ │  TICKET   │    │  PAYMENT   │       │
    │SERVICE │ │ SERVICE   │    │  SERVICE   │       │
    │(8004)  │ │ (8003)    │    │  (8002)    │       │         
    └────┬───┘ └─┬─────────┘    └────┬───────┘       │
         │       │                   │               │
    ┌────▼──┐ ┌──▼─────┐         ┌───▼─────┐         │
    │track_ │ │ticket_ │         │payment_ │         │
    │db     │ │db      │         │db       │         │
    └───────┘ └────────┘         └─────────┘         │
                                                     │
                                           ┌─────────▼──────┐
                                           │     bus_db     │
                                           └────────────────┘
```

**Data Flow:**
- Frontend → API Gateway → Services
- Bus Service ← Tracking Service (Real-time data)
- Bus Service → Ticket Service → Payment Service
- Bus Service ↔ BUS_DB (Read/Write)

**Database Mapping (1 Service = 1 Database):**
- Bus Service (8001) ↔ BUS_DB
- Ticket Service (8003) ↔ TICKET_DB  
- Payment Service (8002) ↔ PAYMENT_DB
- Tracking Service (8004) ↔ TRACKING_DB

**Struktur Hubungan:** `Tracking ← Bus → Ticket → Payment`

---

## 👥 Anggota & Peran

| No | Nama | Service | Peran |
|----|------|---------|-------|
| 1  | Sanjaya Fathur Rahman | Bus Service | CRUD Bus, Routes, Manajemen Armada |
| 2  | Muhammad Mufid Taqiyyuddin | Ticket Service | Pemesanan, Reservasi, Laporan Tiket |
| 3  | Falah Adhi Chandra | Payment Service | Transaksi, Status Pembayaran, Invoice |
| 4  | Sultan Afdan Zamzami | Tracking Service + API Gateway | Real-time Tracking, Route Monitoring |

---

## 📦 Prasyarat

- **Git**: [https://git-scm.com/](https://git-scm.com/)
- **PHP 8.2+**: [https://www.php.net/](https://www.php.net/) atau [Laragon](https://laragon.org/)
- **Composer**: [https://getcomposer.org/](https://getcomposer.org/)
- **Node.js 16+**: [https://nodejs.org/](https://nodejs.org/)
- **Postman** (optional): Untuk testing API

---

## ⚙️ Setup Cloning

Jalankan langkah-langkah berikut SEKALI saja saat melakukan clone repository:

### 1. Clone Repository
```bash
git clone https://github.com/zamzamyst/KELOMPOK3-UTS-IAE.git
cd KELOMPOK3-UTS-IAE
```

### 2. Setup API Gateway
```bash
cd api-gateway

# Install dependencies
npm install

# Buat file .env jika belum ada
copy .env.example .env  # Windows
# atau
cp .env.example .env    # Linux/Mac

cd ..
```

### 3. Setup Bus Service
```bash
cd bus-service

# Install PHP dependencies
composer install

# Install Node dependencies (untuk asset compilation)
npm install

# Buat file .env dari template
copy .env.example .env  # Windows
# atau
cp .env.example .env    # Linux/Mac

# Generate APP_KEY
php artisan key:generate

# Buat database
touch ./database/bus.sqlite

# Jalankan migration database
php artisan migrate

cd ..
```

### 4. Setup Ticket Service
```bash
cd ticket-service

# Install PHP dependencies
composer install

# Install Node dependencies (untuk asset compilation)
npm install

# Buat file .env dari template
copy .env.example .env  # Windows
# atau
cp .env.example .env    # Linux/Mac

# Generate APP_KEY
php artisan key:generate

# Buat database
touch ./database/ticket.sqlite

# Jalankan migration database
php artisan migrate

cd ..
```

### 5. Setup Payment Service
```bash
cd payment-service

# Install PHP dependencies
composer install

# Install Node dependencies (untuk asset compilation)
npm install

# Buat file .env dari template
copy .env.example .env  # Windows
# atau
cp .env.example .env    # Linux/Mac

# Generate APP_KEY
php artisan key:generate

# Buat database
touch ./database/payment.sqlite

# Jalankan migration database
php artisan migrate

cd ..
```

### 6. Setup Tracking Service
```bash
cd tracking-service

# Install PHP dependencies
composer install

# Install Node dependencies (untuk asset compilation)
npm install

# Buat file .env dari template
copy .env.example .env  # Windows
# atau
cp .env.example .env    # Linux/Mac

# Generate APP_KEY
php artisan key:generate

# Buat database
touch ./database/tracking.sqlite

# Jalankan migration database
php artisan migrate

cd ..
```

### 7. Verifikasi Setup

Pastikan semua service sudah ter-setup dengan memeriksa file berikut ada dan sudah dikonfigurasi:

- ✅ `api-gateway/.env` - Konfigurasi API Gateway
- ✅ `bus-service/.env` - Konfigurasi Bus Service
- ✅ `ticket-service/.env` - Konfigurasi Ticket Service
- ✅ `payment-service/.env` - Konfigurasi Payment Service
- ✅ `tracking-service/.env` - Konfigurasi Tracking Service

**Catatan:** Semua database file akan dibuat otomatis di folder `database/` masing-masing service saat melakukan `php artisan migrate`.

---

## 🚀 Panduan Menjalankan

### 📍 Urutan Start Services (PENTING!)

Jalankan dalam urutan ini di **terminal terpisah**:

#### 1️⃣ Terminal 1: API Gateway (WAJIB PERTAMA)
```bash
cd api-gateway
npm start
# Expected: API Gateway running on port 4000
```

#### 2️⃣ Terminal 2: Bus Service
```bash
cd bus-service
php artisan serve --port=8001
```

#### 3️⃣ Terminal 3: Ticket Service
```bash
cd ticket-service
php artisan serve --port=8003
```

#### 4️⃣ Terminal 4: Payment Service
```bash
cd payment-service
php artisan serve --port=8002
```

#### 5️⃣ Terminal 5: Tracking Service
```bash
cd tracking-service
php artisan serve --port=8004
```

#### 6️⃣ Terminal 6: Frontend (TERAKHIR)
```bash
cd frontend
php -S 127.0.0.1:8000
# Access: http://localhost:8000
```

---

## 🔧 Environment Variables

### API Gateway (api-gateway/.env)
```env
PORT=4000
NODE_ENV=development
BUS_SERVICE=http://localhost:8001
TICKET_SERVICE=http://localhost:8003
PAYMENT_SERVICE=http://localhost:8002
TRACKING_SERVICE=http://localhost:8004
```

### Bus Service (bus-service/.env)
```env
APP_NAME=BusService
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001
DB_CONNECTION=sqlite
DB_DATABASE=database/bus.db
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Ticket Service (ticket-service/.env)
```env
APP_NAME=TicketService
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8003
DB_CONNECTION=sqlite
DB_DATABASE=database/ticket.db
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Payment Service (payment-service/.env)
```env
APP_NAME=PaymentService
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8002
DB_CONNECTION=sqlite
DB_DATABASE=database/payment.db
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Tracking Service (tracking-service/.env)
```env
APP_NAME=TrackingService
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8004
DB_CONNECTION=sqlite
DB_DATABASE=database/tracking.db
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Frontend (frontend/config.php)
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

---

## 📌 API Endpoints Ringkas

Dokumentasi lengkap tersedia di: **[docs/api/](docs/api/README.md)**

### Via API Gateway (Base URL: `http://localhost:4000`)

| Service | Method | Endpoint | Deskripsi |
|---------|--------|----------|-----------|
| **Bus** | GET | `/api/bus-service/api/buses` | Ambil semua bus |
| **Bus** | POST | `/api/bus-service/api/buses` | Buat bus baru |
| **Bus** | GET | `/api/bus-service/api/buses/{id}` | Detail bus |
| **Bus** | PUT | `/api/bus-service/api/buses/{id}` | Update bus |
| **Bus** | DELETE | `/api/bus-service/api/buses/{id}` | Hapus bus |
| **Ticket** | GET | `/api/ticket-service/api/tickets` | Ambil semua tiket |
| **Ticket** | GET | `/api/ticket-service/api/tickets/{id}` | Detail tiket |
| **Ticket** | POST | `/api/ticket-service/ticket/api/tickets` | Buat tiket baru |
| **Ticket** | PUT | `/api/ticket-service/api/tickets/{id}` | Update tiket |
| **Ticket** | DELETE | `/api/ticket-service/api/tickets/{id}` | Hapus tiket |
| **Payment** | GET | `/api/payment-service/api/payments` | Ambil semua pembayaran |
| **Payment** | GET | `/api/payment-service/api/payments/{id}` | Detail pembayaran |
| **Payment** | POST | `/api/payment-service/api/payments` | Proses pembayaran |
| **Payment** | PUT | `/api/payment-service/api/payments/{id}` | Update pembayaran |
| **Payment** | DELETE | `/api/payment-service/api/payments/{id}` | Delete pembayaran |
| **Tracking** | GET | `/api/tracking-service/api/tracking` | Ambil tracking |
| **Tracking** | GET | `/api/tracking-service/api/tracking{id}` | Detail tracking |
| **Tracking** | POST | `/api/tracking-service/api/tracking` | Buat tracking baru |
| **Tracking** | POST | `/api/tracking-service/api/tracking` | Update tracking (jika bus_id sudah pernah di-track) |
| **Tracking** | DELETE | `/api/tracking-service/api/tracking/{id}` | Hapus tracking baru |

### Direct Service Access (Bypass Gateway)

- **Bus Service**: `http://localhost:8001/api/buses`
- **Ticket Service**: `http://localhost:8003/api/tickets`
- **Payment Service**: `http://localhost:8002/api/payments`
- **Tracking Service**: `http://localhost:8004/api/tracking`

> 📖 **Dokumentasi lengkap request/response: [docs/api/README.md](docs/api/README.md)**

---

## 📁 Struktur Proyek

```
KELOMPOK3-UTS-IAE/
├── README.md                                    # Dokumentasi ini
├── KELOMPOK3-UTS-IAE.postman_collection.json   # Postman collection
│
├── api-gateway/                                 # API Gateway (Node.js/Express)
│   ├── index.js
│   ├── package.json
│   └── node_modules/
│
├── bus-service/                                 # Bus Service (Laravel)
│   ├── app/                                    # Controllers, Models
│   ├── config/                                 # Configuration
│   ├── database/                               # Migrations, Seeders
│   ├── routes/                                 # API routes
│   ├── storage/                                # Logs
│   ├── composer.json
│   ├── artisan
│   └── .env
│
├── ticket-service/                             # Ticket Service (Laravel)
│   └── [Similar to bus-service]
│
├── payment-service/                            # Payment Service (Laravel)
│   └── [Similar to bus-service]
│
├── tracking-service/                           # Tracking Service (Laravel)
│   └── [Similar to bus-service]
│
├── frontend/                                   # Frontend (PHP)
│   ├── index.html
│   ├── index.php
│   ├── config.php
│   └── README.md
│
└── docs/                                       # Dokumentasi
    ├── api/                                    # API Documentation
    │   ├── README.md
    │   ├── bus-service.md
    │   ├── ticket-service.md
    │   ├── payment-service.md
    │   └── tracking-service.md
    └── architecture.md
```

---

## 🧪 Testing & Troubleshooting

### Import Postman Collection

1. Buka Postman
2. Click `Import` → Pilih `KELOMPOK3-UTS-IAE.postman_collection.json`
3. Setup environment variables untuk port dan URL
4. Jalankan test collections

### Error: "Port already in use"

```bash
# Windows
netstat -ano | findstr :4000
taskkill /PID <PID> /F

# Linux/Mac
lsof -i :4000
kill -9 <PID>
```

### Error: "Database connection failed"

```bash
# Ensure .env file exists dan migrate
php artisan migrate --env=local
```

### Error: "CORS error"

- Pastikan API Gateway sudah running
- Cek URL di config.php sudah benar
- Verifikasi CORS setting di api-gateway/index.js

---

## 📞 Quick Links

- **Repository**: https://github.com/zamzamyst/KELOMPOK3-UTS-IAE
- **Postman Collection**: `KELOMPOK3-UTS-IAE.postman_collection.json`
- **API Documentation**: [docs/api/README.md](docs/api/README.md)

---

**Last Updated:** November 2025  
**Status:** In Development ✓
