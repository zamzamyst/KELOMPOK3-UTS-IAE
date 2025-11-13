# Bus Service - Detailed API Documentation

Dokumentasi detail untuk Bus Service API.

## Overview

Bus Service menangani operasi CRUD untuk data bus dan manajemen armada dalam sistem BusTrack Hub.

## Base URL

- **Gateway**: `http://localhost:4000/bus`
- **Direct**: `http://localhost:8001`

## Endpoints

### GET /api/buses
Ambil daftar semua bus dengan pagination dan filtering.

**Query Parameters:**
- `limit` (integer, optional): Jumlah item per halaman (default: 10)
- `offset` (integer, optional): Offset untuk pagination (default: 0)
- `status` (string, optional): Filter berdasarkan status
- `driver` (string, optional): Cari berdasarkan nama driver

**Example Request:**
```bash
curl -X GET "http://localhost:4000/bus/api/buses?limit=5&offset=0&status=active" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Bus Premium A1",
      "capacity": 45,
      "license_plate": "B 1234 AB",
      "status": "active",
      "driver": "Budi Santoso",
      "created_at": "2024-11-13T10:00:00Z",
      "updated_at": "2024-11-13T10:00:00Z"
    }
  ],
  "pagination": {
    "total": 10,
    "limit": 5,
    "offset": 0,
    "pages": 2
  }
}
```

---

### GET /api/buses/{id}
Ambil detail bus berdasarkan ID.

**Path Parameters:**
- `id` (integer, required): ID bus

**Example Request:**
```bash
curl -X GET "http://localhost:4000/bus/api/buses/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Bus Premium A1",
    "capacity": 45,
    "license_plate": "B 1234 AB",
    "status": "active",
    "driver": "Budi Santoso",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T10:00:00Z"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Bus not found"
}
```

---

### POST /api/buses
Buat bus baru.

**Request Body:**
```json
{
  "name": "Bus Standard B1",
  "capacity": 40,
  "license_plate": "B 9012 EF",
  "status": "active",
  "driver": "Andi Wijaya"
}
```

**Required Fields:**
- `name` (string): Nama bus
- `capacity` (integer): Kapasitas penumpang
- `license_plate` (string, unique): Nomor plat kendaraan
- `status` (string): Status (active, inactive, maintenance)
- `driver` (string): Nama driver

**Example Request:**
```bash
curl -X POST "http://localhost:4000/bus/api/buses" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bus Standard B1",
    "capacity": 40,
    "license_plate": "B 9012 EF",
    "status": "active",
    "driver": "Andi Wijaya"
  }'
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Bus created successfully",
  "data": {
    "id": 11,
    "name": "Bus Standard B1",
    "capacity": 40,
    "license_plate": "B 9012 EF",
    "status": "active",
    "driver": "Andi Wijaya",
    "created_at": "2024-11-13T14:00:00Z",
    "updated_at": "2024-11-13T14:00:00Z"
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "license_plate": ["License plate already exists"]
  }
}
```

---

### PUT /api/buses/{id}
Update data bus.

**Path Parameters:**
- `id` (integer, required): ID bus

**Request Body:**
```json
{
  "name": "Bus Premium A1 Updated",
  "capacity": 50,
  "status": "maintenance",
  "driver": "Budi Santoso"
}
```

**Example Request:**
```bash
curl -X PUT "http://localhost:4000/bus/api/buses/1" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bus Premium A1 Updated",
    "capacity": 50,
    "status": "maintenance"
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Bus updated successfully",
  "data": {
    "id": 1,
    "name": "Bus Premium A1 Updated",
    "capacity": 50,
    "license_plate": "B 1234 AB",
    "status": "maintenance",
    "driver": "Budi Santoso",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T15:00:00Z"
  }
}
```

---

### DELETE /api/buses/{id}
Hapus bus.

**Path Parameters:**
- `id` (integer, required): ID bus

**Example Request:**
```bash
curl -X DELETE "http://localhost:4000/bus/api/buses/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Bus deleted successfully"
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Bus not found"
}
```

---

## Status Codes

- **200**: Operasi berhasil
- **201**: Resource berhasil dibuat
- **400**: Input tidak valid
- **404**: Bus tidak ditemukan
- **500**: Server error

---

**Last Updated:** November 2024
