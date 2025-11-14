# Tracking Service - Detailed API Documentation

Dokumentasi detail untuk Tracking Service API.

## Overview

Tracking Service menangani pelacakan lokasi bus real-time, riwayat perjalanan, dan status perjalanan.

## Base URL

- **Gateway**: `http://localhost:4000/api/tracking-service`
- **Direct**: `http://localhost:8004`

## Endpoints

### GET /api/trackings
Ambil daftar semua data pelacakan.

**Example Request:**
```bash
curl -X GET "http://localhost:4000/api/tracking-services/api/trackings" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
    "id": 2,
    "lat": -6.9615570335663,
    "lng": 107.61720872705,
    "bus": {
        "id": 2,
        "name": "Bus Prima Jasa",
        "plate_number": "B 2222 CD",
        "capacity": 30
    },
    "route": {
        "id": 1,
        "code": "PPP111",
        "origin": "Depok",
        "destination": "Bandung",
        "stops": [
            "Banten",
            "Cirebon",
            "Bekasi"
        ]
    },
    "schedule": {
        "id": 1,
        "departure_at": "2025-12-01 08:00:00",
        "arrival_at": "2025-12-01 10:00:00",
        "available_seats": 36,
        "price": 30000
    },
    "created_at": "2025-11-14T16:14:31.000000Z"
}
```

---

### GET /api/tracking/{id}
Ambil detail pelacakan berdasarkan ID.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Query Parameters:**
- `bus_id` (integer): Filter berdasarkan ID bus yang sudah pernah di-track

**Example Request:**
```bash
curl -X GET "http://localhost:4000/api/tracking-service/api/trackings/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
    "id": 1,
    "lat": -6.9615570335663,
    "lng": 107.61720872705,
    "bus": {
        "id": 2,
        "name": "Bus Prima Jasa",
        "plate_number": "B 2222 CD",
        "capacity": 30
    },
    "route": {
        "id": 1,
        "code": "PPP111",
        "origin": "Depok",
        "destination": "Bandung",
        "stops": [
            "Banten",
            "Cirebon",
            "Bekasi"
        ]
    },
    "schedule": {
        "id": 1,
        "departure_at": "2025-12-01 08:00:00",
        "arrival_at": "2025-12-01 10:00:00",
        "available_seats": 36,
        "price": 30000
    },
    "created_at": "2025-11-14T16:14:31.000000Z"
}
```

---

### POST /api/trackings
Buat data pelacakan baru.

**Request Body:**
```json
{
  "bus_id": 1,
}
```

**Required Fields:**
- `bus_id` (integer): ID bus yang tersedia/sudah pernah dibuat

**Example Request:**
```bash
curl -X POST "http://localhost:4000/api/tracking-service/api/tracking" \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 2
  }'
```

**Success Response (201):**
```json
{
    "id": 2,
    "lat": -6.9615570335663,
    "lng": 107.61720872705,
    "bus": {
        "id": 2,
        "name": "Bus Prima Jasa",
        "plate_number": "B 2222 CD",
        "capacity": 30
    },
    "route": {
        "id": 1,
        "code": "PPP111",
        "origin": "Depok",
        "destination": "Bandung",
        "stops": [
            "Banten",
            "Cirebon",
            "Bekasi"
        ]
    },
    "schedule": {
        "id": 1,
        "departure_at": "2025-12-01 08:00:00",
        "arrival_at": "2025-12-01 10:00:00",
        "available_seats": 36,
        "price": 30000
    },
    "created_at": "2025-11-14T16:14:31.000000Z"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Bus tidak tersedia!"
}
```

---

### POST /api/trackings/{id}
Update lokasi pelacakan.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Request Body:**
```json
{
  "bus_id": 2,
}
```

**Required Fields:**
- `bus_id` (integer): ID bus yang tersedia/sudah pernah di-track

**Example Request:**
```bash
curl -X POST "http://localhost:4000/api/tracking-service/api/tracking" \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 2
  }'
```

**Success Response (200):**
```json
{
    "id": 3,
    "lat": -6.8965103451805,
    "lng": 107.61971682149,
    "bus": {
        "id": 2,
        "name": "Bus Prima Jasa",
        "plate_number": "B 2222 CD",
        "capacity": 30
    },
    "route": {
        "id": 1,
        "code": "PPP111",
        "origin": "Depok",
        "destination": "Bandung",
        "stops": [
            "Banten",
            "Cirebon",
            "Bekasi"
        ]
    },
    "schedule": {
        "id": 1,
        "departure_at": "2025-12-01 08:00:00",
        "arrival_at": "2025-12-01 10:00:00",
        "available_seats": 36,
        "price": 30000
    },
    "created_at": "2025-11-14T17:40:58.000000Z"
}
```

---

### DELETE /api/trackings/{id}
Hapus data pelacakan.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Example Request:**
```bash
curl -X DELETE "http://localhost:4000/api/tracking-service/api/trackings/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Data Tracking berhasil dihapus!"
}
```

---

## Location Data

Sistem tracking menggunakan koordinat GPS (Latitude, Longitude) dalam format desimal:
- **Latitude (Lat)**: -90 hingga +90 (positif = utara, negatif = selatan)
- **Longitude (Lng)**: -180 hingga +180 (positif = timur, negatif = barat)

**Contoh koordinat Jakarta:** -6.2088, 106.8456

---

**Last Updated:** November 2025
