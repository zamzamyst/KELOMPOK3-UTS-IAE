# API Documentation - BusTrack Hub

Dokumentasi lengkap API untuk sistem BusTrack Hub microservices.

## 📋 Daftar Isi

- [Base URLs](#base-urls)
- [Authentication](#authentication)
- [Bus Service API](#bus-service-api)
- [Ticket Service API](#ticket-service-api)
- [Payment Service API](#payment-service-api)
- [Tracking Service API](#tracking-service-api)
- [Error Handling](#error-handling)
- [Response Format](#response-format)

---

## 🔗 Base URLs

| Service | URL | Port |
|---------|-----|------|
| **API Gateway** | `http://localhost:4000` | 4000 |
| **Bus Service** | `http://localhost:8001` | 8001 |
| **Ticket Service** | `http://localhost:8003` | 8003 |
| **Payment Service** | `http://localhost:8002` | 8002 |
| **Tracking Service** | `http://localhost:8004` | 8004 |

---

## 🔐 Authentication

Semua endpoint memerlukan header autentikasi (untuk production).

```http
Authorization: Bearer <token>
Content-Type: application/json
```

---

## 🚌 Bus Service API

**Base URL:** `http://localhost:4000/bus` (via Gateway) atau `http://localhost:8001` (Direct)

### 1. Get All Buses

```http
GET /api/buses
```

**Query Parameters:**
- `limit` (int, optional): Jumlah data per halaman (default: 10)
- `offset` (int, optional): Offset untuk pagination (default: 0)
- `status` (string, optional): Filter by status (active, inactive, maintenance)

**Response (200 OK):**
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
    "limit": 10,
    "offset": 0
  }
}
```

### 2. Get Bus by ID

```http
GET /api/buses/{id}
```

**Response (200 OK):**
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

### 3. Create Bus

```http
POST /api/buses
Content-Type: application/json

{
  "name": "Bus Standard A2",
  "capacity": 40,
  "license_plate": "B 5678 CD",
  "status": "active",
  "driver": "Andi Wijaya"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Bus created successfully",
  "data": {
    "id": 2,
    "name": "Bus Standard A2",
    "capacity": 40,
    "license_plate": "B 5678 CD",
    "status": "active",
    "driver": "Andi Wijaya",
    "created_at": "2024-11-13T11:00:00Z",
    "updated_at": "2024-11-13T11:00:00Z"
  }
}
```

### 4. Update Bus

```http
PUT /api/buses/{id}
Content-Type: application/json

{
  "name": "Bus Premium A1 Updated",
  "capacity": 50,
  "status": "maintenance"
}
```

**Response (200 OK):**
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
    "updated_at": "2024-11-13T12:00:00Z"
  }
}
```

### 5. Delete Bus

```http
DELETE /api/buses/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Bus deleted successfully"
}
```

---

## 🎫 Ticket Service API

**Base URL:** `http://localhost:4000/ticket` (via Gateway) atau `http://localhost:8003` (Direct)

### 1. Get All Tickets

```http
GET /api/tickets
```

**Query Parameters:**
- `limit` (int, optional): Default 10
- `offset` (int, optional): Default 0
- `bus_id` (int, optional): Filter by bus
- `status` (string, optional): Filter by status (available, booked, cancelled)

**Response (200 OK):**
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
    "total": 1,
    "limit": 10,
    "offset": 0
  }
}
```

### 2. Get Ticket by ID

```http
GET /api/tickets/{id}
```

**Response (200 OK):**
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

### 3. Create Ticket (Book)

```http
POST /api/tickets
Content-Type: application/json

{
  "bus_id": 1,
  "seat_number": "A1",
  "passenger_name": "John Doe",
  "passenger_email": "john@example.com",
  "passenger_phone": "08123456789",
  "price": 150000
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Ticket booked successfully",
  "data": {
    "id": 2,
    "bus_id": 1,
    "seat_number": "A2",
    "passenger_name": "John Doe",
    "passenger_email": "john@example.com",
    "passenger_phone": "08123456789",
    "status": "booked",
    "price": 150000,
    "created_at": "2024-11-13T11:00:00Z",
    "updated_at": "2024-11-13T11:00:00Z"
  }
}
```

### 4. Update Ticket

```http
PUT /api/tickets/{id}
Content-Type: application/json

{
  "status": "cancelled"
}
```

**Response (200 OK):**
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

### 5. Delete Ticket

```http
DELETE /api/tickets/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Ticket deleted successfully"
}
```

---

## 💳 Payment Service API

**Base URL:** `http://localhost:4000/payment` (via Gateway) atau `http://localhost:8002` (Direct)

### 1. Get All Payments

```http
GET /api/payments
```

**Query Parameters:**
- `limit` (int, optional): Default 10
- `offset` (int, optional): Default 0
- `status` (string, optional): Filter by status (pending, completed, failed, refunded)

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "ticket_id": 1,
      "amount": 150000,
      "status": "completed",
      "payment_method": "transfer",
      "reference_number": "TRF20241113001",
      "description": "Pembayaran tiket bus",
      "created_at": "2024-11-13T10:00:00Z",
      "updated_at": "2024-11-13T10:00:00Z"
    }
  ],
  "pagination": {
    "total": 1,
    "limit": 10,
    "offset": 0
  }
}
```

### 2. Get Payment by ID

```http
GET /api/payments/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ticket_id": 1,
    "amount": 150000,
    "status": "completed",
    "payment_method": "transfer",
    "reference_number": "TRF20241113001",
    "description": "Pembayaran tiket bus",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T10:00:00Z"
  }
}
```

### 3. Create Payment

```http
POST /api/payments
Content-Type: application/json

{
  "ticket_id": 1,
  "amount": 150000,
  "payment_method": "transfer",
  "reference_number": "TRF20241113001",
  "description": "Pembayaran tiket bus"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Payment created successfully",
  "data": {
    "id": 2,
    "ticket_id": 1,
    "amount": 150000,
    "status": "pending",
    "payment_method": "transfer",
    "reference_number": "TRF20241113001",
    "description": "Pembayaran tiket bus",
    "created_at": "2024-11-13T11:00:00Z",
    "updated_at": "2024-11-13T11:00:00Z"
  }
}
```

### 4. Update Payment Status

```http
PUT /api/payments/{id}
Content-Type: application/json

{
  "status": "completed"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Payment updated successfully",
  "data": {
    "id": 1,
    "ticket_id": 1,
    "amount": 150000,
    "status": "completed",
    "payment_method": "transfer",
    "reference_number": "TRF20241113001",
    "description": "Pembayaran tiket bus",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T12:00:00Z"
  }
}
```

### 5. Delete Payment

```http
DELETE /api/payments/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Payment deleted successfully"
}
```

---

## 📍 Tracking Service API

**Base URL:** `http://localhost:4000/tracking` (via Gateway) atau `http://localhost:8004` (Direct)

### 1. Get All Tracking

```http
GET /api/tracking
```

**Query Parameters:**
- `limit` (int, optional): Default 10
- `offset` (int, optional): Default 0
- `bus_id` (int, optional): Filter by bus
- `status` (string, optional): Filter by status (scheduled, on_route, completed, delayed)

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "bus_id": 1,
      "route_name": "Jakarta - Bandung",
      "departure_time": "2024-11-13T08:00:00Z",
      "estimated_arrival": "2024-11-13T10:30:00Z",
      "current_location": "-6.9271,107.6428",
      "status": "on_route",
      "distance_traveled": 45.5,
      "total_distance": 180,
      "created_at": "2024-11-13T08:00:00Z",
      "updated_at": "2024-11-13T09:15:00Z"
    }
  ],
  "pagination": {
    "total": 1,
    "limit": 10,
    "offset": 0
  }
}
```

### 2. Get Tracking by ID

```http
GET /api/tracking/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "bus_id": 1,
    "route_name": "Jakarta - Bandung",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T10:30:00Z",
    "current_location": "-6.9271,107.6428",
    "status": "on_route",
    "distance_traveled": 45.5,
    "total_distance": 180,
    "created_at": "2024-11-13T08:00:00Z",
    "updated_at": "2024-11-13T09:15:00Z"
  }
}
```

### 3. Create Tracking

```http
POST /api/tracking
Content-Type: application/json

{
  "bus_id": 1,
  "route_name": "Jakarta - Bandung",
  "departure_time": "2024-11-13T08:00:00Z",
  "estimated_arrival": "2024-11-13T10:30:00Z",
  "total_distance": 180
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Tracking created successfully",
  "data": {
    "id": 2,
    "bus_id": 1,
    "route_name": "Jakarta - Bandung",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T10:30:00Z",
    "current_location": "-6.1257,106.8650",
    "status": "scheduled",
    "distance_traveled": 0,
    "total_distance": 180,
    "created_at": "2024-11-13T08:00:00Z",
    "updated_at": "2024-11-13T08:00:00Z"
  }
}
```

### 4. Update Tracking (Real-time Location)

```http
PUT /api/tracking/{id}
Content-Type: application/json

{
  "current_location": "-6.9271,107.6428",
  "distance_traveled": 45.5,
  "status": "on_route"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Tracking updated successfully",
  "data": {
    "id": 1,
    "bus_id": 1,
    "route_name": "Jakarta - Bandung",
    "departure_time": "2024-11-13T08:00:00Z",
    "estimated_arrival": "2024-11-13T10:30:00Z",
    "current_location": "-6.9271,107.6428",
    "status": "on_route",
    "distance_traveled": 45.5,
    "total_distance": 180,
    "created_at": "2024-11-13T08:00:00Z",
    "updated_at": "2024-11-13T09:15:00Z"
  }
}
```

### 5. Delete Tracking

```http
DELETE /api/tracking/{id}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Tracking deleted successfully"
}
```

---

## ⚠️ Error Handling

### Error Response Format

Semua error response mengikuti format ini:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error detail"]
  }
}
```

### Common HTTP Status Codes

| Status | Meaning |
|--------|---------|
| **200** | OK - Request berhasil |
| **201** | Created - Resource berhasil dibuat |
| **400** | Bad Request - Input tidak valid |
| **401** | Unauthorized - Authentication required |
| **403** | Forbidden - Access denied |
| **404** | Not Found - Resource tidak ditemukan |
| **500** | Internal Server Error - Server error |
| **503** | Service Unavailable - Service sedang maintenance |

### Example Error Response (400 Bad Request)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required"],
    "license_plate": ["The license plate must be unique"]
  }
}
```

### Example Error Response (404 Not Found)

```json
{
  "success": false,
  "message": "Bus not found"
}
```

---

## 📦 Response Format

Semua API responses mengikuti standar format berikut:

### Success Response
```json
{
  "success": true,
  "message": "Optional message",
  "data": {}
}
```

### Paginated Response
```json
{
  "success": true,
  "data": [],
  "pagination": {
    "total": 100,
    "limit": 10,
    "offset": 0,
    "pages": 10
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

---

## 🧪 Testing dengan Postman

1. Import collection: `KELOMPOK3-UTS-IAE.postman_collection.json`
2. Setup environment:
   ```json
   {
     "api_gateway_url": "http://localhost:4000",
     "bus_service_url": "http://localhost:8001",
     "ticket_service_url": "http://localhost:8003",
     "payment_service_url": "http://localhost:8002",
     "tracking_service_url": "http://localhost:8004"
   }
   ```
3. Jalankan requests sesuai dokumentasi di atas

---

## 🔗 Quick Links

- [Main README](../../README.md)
- [Bus Service Details](bus-service.md)
- [Ticket Service Details](ticket-service.md)
- [Payment Service Details](payment-service.md)
- [Tracking Service Details](tracking-service.md)

---

**Last Updated:** November 2024  
**Version:** 1.0
