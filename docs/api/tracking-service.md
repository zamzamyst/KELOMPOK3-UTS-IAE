# Tracking Service - Detailed API Documentation

Dokumentasi detail untuk Tracking Service API.

## Overview

Tracking Service menangani pelacakan lokasi bus real-time, riwayat perjalanan, dan status perjalanan.

## Base URL

- **Gateway**: `http://localhost:4000/tracking`
- **Direct**: `http://localhost:8004`

## Endpoints

### GET /api/tracking
Ambil daftar semua data pelacakan dengan pagination dan filtering.

**Query Parameters:**
- `limit` (integer, optional): Jumlah item per halaman (default: 10)
- `offset` (integer, optional): Offset untuk pagination (default: 0)
- `bus_id` (integer, optional): Filter berdasarkan ID bus
- `status` (string, optional): Filter berdasarkan status (on_route, arrived, delayed, cancelled)

**Example Request:**
```bash
curl -X GET "http://localhost:4000/tracking/api/tracking?status=on_route&limit=5" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "bus_id": 1,
      "current_latitude": -6.2088,
      "current_longitude": 106.8456,
      "status": "on_route",
      "departure_time": "2024-11-13T08:00:00Z",
      "estimated_arrival": "2024-11-13T12:30:00Z",
      "distance_traveled": 45.5,
      "total_distance": 120,
      "updated_at": "2024-11-13T10:15:00Z"
    }
  ],
  "pagination": {
    "total": 15,
    "limit": 5,
    "offset": 0,
    "pages": 3
  }
}
```

---

### GET /api/tracking/{id}
Ambil detail pelacakan berdasarkan ID.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Example Request:**
```bash
curl -X GET "http://localhost:4000/tracking/api/tracking/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "bus_id": 1,
    "current_latitude": -6.2088,
    "current_longitude": 106.8456,
    "status": "on_route",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T12:30:00Z",
    "distance_traveled": 45.5,
    "total_distance": 120,
    "stops_completed": 3,
    "total_stops": 8,
    "driver_name": "Budi Santoso",
    "vehicle_speed": 75,
    "updated_at": "2024-11-13T10:15:00Z"
  }
}
```

---

### POST /api/tracking
Buat data pelacakan baru.

**Request Body:**
```json
{
  "bus_id": 1,
  "current_latitude": -6.2088,
  "current_longitude": 106.8456,
  "status": "on_route",
  "departure_time": "2024-11-13T08:00:00Z",
  "estimated_arrival": "2024-11-13T12:30:00Z",
  "total_distance": 120
}
```

**Required Fields:**
- `bus_id` (integer): ID bus
- `current_latitude` (decimal): Latitude lokasi saat ini
- `current_longitude` (decimal): Longitude lokasi saat ini
- `status` (string): Status perjalanan
- `departure_time` (datetime): Waktu keberangkatan
- `estimated_arrival` (datetime): Perkiraan waktu tiba
- `total_distance` (decimal): Total jarak perjalanan

**Example Request:**
```bash
curl -X POST "http://localhost:4000/tracking/api/tracking" \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 1,
    "current_latitude": -6.2088,
    "current_longitude": 106.8456,
    "status": "on_route",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T12:30:00Z",
    "total_distance": 120
  }'
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Tracking data created successfully",
  "data": {
    "id": 2,
    "bus_id": 1,
    "current_latitude": -6.2088,
    "current_longitude": 106.8456,
    "status": "on_route",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T12:30:00Z",
    "distance_traveled": 0,
    "total_distance": 120,
    "stops_completed": 0,
    "total_stops": 0,
    "created_at": "2024-11-13T11:00:00Z",
    "updated_at": "2024-11-13T11:00:00Z"
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "current_latitude": ["Latitude must be between -90 and 90"],
    "current_longitude": ["Longitude must be between -180 and 180"]
  }
}
```

---

### PUT /api/tracking/{id}
Update lokasi dan status pelacakan.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Request Body:**
```json
{
  "current_latitude": -6.2100,
  "current_longitude": 106.8500,
  "status": "on_route",
  "distance_traveled": 50,
  "vehicle_speed": 80
}
```

**Example Request:**
```bash
curl -X PUT "http://localhost:4000/tracking/api/tracking/1" \
  -H "Content-Type: application/json" \
  -d '{
    "current_latitude": -6.2100,
    "current_longitude": 106.8500,
    "status": "on_route",
    "distance_traveled": 50,
    "vehicle_speed": 80
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Tracking data updated successfully",
  "data": {
    "id": 1,
    "bus_id": 1,
    "current_latitude": -6.2100,
    "current_longitude": 106.8500,
    "status": "on_route",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T12:30:00Z",
    "distance_traveled": 50,
    "total_distance": 120,
    "vehicle_speed": 80,
    "stops_completed": 3,
    "total_stops": 8,
    "updated_at": "2024-11-13T10:45:00Z"
  }
}
```

---

### DELETE /api/tracking/{id}
Hapus data pelacakan.

**Path Parameters:**
- `id` (integer, required): ID tracking

**Example Request:**
```bash
curl -X DELETE "http://localhost:4000/tracking/api/tracking/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Tracking data deleted successfully"
}
```

---

## Tracking Status

- **on_route**: Bus sedang perjalanan
- **arrived**: Bus telah tiba di tujuan
- **delayed**: Bus terlambat
- **cancelled**: Perjalanan dibatalkan

---

## Location Data

Sistem tracking menggunakan koordinat GPS (Latitude, Longitude) dalam format desimal:
- **Latitude**: -90 hingga +90 (positif = utara, negatif = selatan)
- **Longitude**: -180 hingga +180 (positif = timur, negatif = barat)

**Contoh koordinat Jakarta:** -6.2088, 106.8456

---

**Last Updated:** November 2024
