# Ticket Service - Detailed API Documentation

Dokumentasi detail untuk Ticket Service API.

## Overview

Ticket Service menangani operasi pemesanan tiket, manajemen reservasi, dan status tiket penumpang.

## Base URL

- **Gateway**: `http://localhost:4000/ticket`
- **Direct**: `http://localhost:8003`

## Endpoints

### GET /api/tickets
Ambil daftar semua tiket dengan pagination dan filtering.

**Query Parameters:**
- `limit` (integer, optional): Jumlah item per halaman (default: 10)
- `offset` (integer, optional): Offset untuk pagination (default: 0)
- `bus_id` (integer, optional): Filter berdasarkan ID bus
- `status` (string, optional): Filter berdasarkan status (available, booked, cancelled)
- `passenger_name` (string, optional): Cari berdasarkan nama penumpang

**Example Request:**
```bash
curl -X GET "http://localhost:4000/ticket/api/tickets?bus_id=1&status=booked&limit=5" \
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
      "seat_number": "A1",
      "passenger_name": "John Doe",
      "passenger_email": "john@example.com",
      "passenger_phone": "08123456789",
      "status": "booked",
      "price": 150000,
      "created_at": "2024-11-13T10:00:00Z",
      "updated_at": "2024-11-13T10:00:00Z"
    }
  ],
  "pagination": {
    "total": 40,
    "limit": 5,
    "offset": 0,
    "pages": 8
  }
}
```

---

### GET /api/tickets/{id}
Ambil detail tiket berdasarkan ID.

**Path Parameters:**
- `id` (integer, required): ID tiket

**Example Request:**
```bash
curl -X GET "http://localhost:4000/ticket/api/tickets/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "bus_id": 1,
    "seat_number": "A1",
    "passenger_name": "John Doe",
    "passenger_email": "john@example.com",
    "passenger_phone": "08123456789",
    "status": "booked",
    "price": 150000,
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T10:00:00Z"
  }
}
```

---

### POST /api/tickets
Buat tiket baru (pemesanan).

**Request Body:**
```json
{
  "bus_id": 1,
  "seat_number": "A2",
  "passenger_name": "Jane Doe",
  "passenger_email": "jane@example.com",
  "passenger_phone": "08987654321",
  "price": 150000
}
```

**Required Fields:**
- `bus_id` (integer): ID bus
- `seat_number` (string, unique per bus): Nomor kursi
- `passenger_name` (string): Nama penumpang
- `passenger_email` (string): Email penumpang
- `passenger_phone` (string): No. telepon penumpang
- `price` (integer): Harga tiket

**Example Request:**
```bash
curl -X POST "http://localhost:4000/ticket/api/tickets" \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 1,
    "seat_number": "A2",
    "passenger_name": "Jane Doe",
    "passenger_email": "jane@example.com",
    "passenger_phone": "08987654321",
    "price": 150000
  }'
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Ticket booked successfully",
  "data": {
    "id": 2,
    "bus_id": 1,
    "seat_number": "A2",
    "passenger_name": "Jane Doe",
    "passenger_email": "jane@example.com",
    "passenger_phone": "08987654321",
    "status": "booked",
    "price": 150000,
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
    "seat_number": ["Seat A2 is already booked"]
  }
}
```

---

### PUT /api/tickets/{id}
Update status tiket.

**Path Parameters:**
- `id` (integer, required): ID tiket

**Request Body:**
```json
{
  "status": "cancelled"
}
```

**Example Request:**
```bash
curl -X PUT "http://localhost:4000/ticket/api/tickets/1" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "cancelled"
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Ticket updated successfully",
  "data": {
    "id": 1,
    "bus_id": 1,
    "seat_number": "A1",
    "passenger_name": "John Doe",
    "passenger_email": "john@example.com",
    "passenger_phone": "08123456789",
    "status": "cancelled",
    "price": 150000,
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T12:00:00Z"
  }
}
```

---

### DELETE /api/tickets/{id}
Hapus tiket.

**Path Parameters:**
- `id` (integer, required): ID tiket

**Example Request:**
```bash
curl -X DELETE "http://localhost:4000/ticket/api/tickets/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Ticket deleted successfully"
}
```

---

## Ticket Status

- **available**: Kursi tersedia untuk dipesan
- **booked**: Kursi telah dipesan
- **cancelled**: Pemesanan dibatalkan

---

**Last Updated:** November 2024
