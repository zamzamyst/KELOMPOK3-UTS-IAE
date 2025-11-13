# Payment Service - Detailed API Documentation

Dokumentasi detail untuk Payment Service API.

## Overview

Payment Service menangani pemrosesan pembayaran, verifikasi transaksi, dan laporan pembayaran.

## Base URL

- **Gateway**: `http://localhost:4000/payment`
- **Direct**: `http://localhost:8002`

## Endpoints

### GET /api/payments
Ambil daftar semua pembayaran dengan pagination dan filtering.

**Query Parameters:**
- `limit` (integer, optional): Jumlah item per halaman (default: 10)
- `offset` (integer, optional): Offset untuk pagination (default: 0)
- `status` (string, optional): Filter berdasarkan status (pending, completed, failed, refunded)
- `ticket_id` (integer, optional): Filter berdasarkan ID tiket
- `payment_method` (string, optional): Filter berdasarkan metode pembayaran

**Example Request:**
```bash
curl -X GET "http://localhost:4000/payment/api/payments?status=completed&limit=5" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "ticket_id": 1,
      "amount": 150000,
      "payment_method": "bank_transfer",
      "status": "completed",
      "transaction_id": "TXN-2024-001",
      "reference_number": "INV-2024-001",
      "created_at": "2024-11-13T10:00:00Z",
      "updated_at": "2024-11-13T10:15:00Z"
    }
  ],
  "pagination": {
    "total": 25,
    "limit": 5,
    "offset": 0,
    "pages": 5
  }
}
```

---

### GET /api/payments/{id}
Ambil detail pembayaran berdasarkan ID.

**Path Parameters:**
- `id` (integer, required): ID pembayaran

**Example Request:**
```bash
curl -X GET "http://localhost:4000/payment/api/payments/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ticket_id": 1,
    "amount": 150000,
    "payment_method": "bank_transfer",
    "status": "completed",
    "transaction_id": "TXN-2024-001",
    "reference_number": "INV-2024-001",
    "payer_name": "John Doe",
    "payer_email": "john@example.com",
    "description": "Payment for Bus Ticket",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T10:15:00Z"
  }
}
```

---

### POST /api/payments
Buat pembayaran baru.

**Request Body:**
```json
{
  "ticket_id": 1,
  "amount": 150000,
  "payment_method": "bank_transfer",
  "payer_name": "John Doe",
  "payer_email": "john@example.com",
  "description": "Payment for Bus Ticket"
}
```

**Required Fields:**
- `ticket_id` (integer): ID tiket yang dibayar
- `amount` (integer): Nominal pembayaran
- `payment_method` (string): Metode pembayaran (bank_transfer, credit_card, e_wallet)
- `payer_name` (string): Nama pembayar
- `payer_email` (string): Email pembayar
- `description` (string, optional): Deskripsi pembayaran

**Example Request:**
```bash
curl -X POST "http://localhost:4000/payment/api/payments" \
  -H "Content-Type: application/json" \
  -d '{
    "ticket_id": 1,
    "amount": 150000,
    "payment_method": "bank_transfer",
    "payer_name": "John Doe",
    "payer_email": "john@example.com",
    "description": "Payment for Bus Ticket"
  }'
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Payment created successfully",
  "data": {
    "id": 2,
    "ticket_id": 1,
    "amount": 150000,
    "payment_method": "bank_transfer",
    "status": "pending",
    "transaction_id": "TXN-2024-002",
    "reference_number": "INV-2024-002",
    "payer_name": "John Doe",
    "payer_email": "john@example.com",
    "description": "Payment for Bus Ticket",
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
    "amount": ["Amount must be greater than 0"],
    "payment_method": ["Invalid payment method"]
  }
}
```

---

### PUT /api/payments/{id}
Update status pembayaran.

**Path Parameters:**
- `id` (integer, required): ID pembayaran

**Request Body:**
```json
{
  "status": "completed"
}
```

**Example Request:**
```bash
curl -X PUT "http://localhost:4000/payment/api/payments/1" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed"
  }'
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Payment status updated successfully",
  "data": {
    "id": 1,
    "ticket_id": 1,
    "amount": 150000,
    "payment_method": "bank_transfer",
    "status": "completed",
    "transaction_id": "TXN-2024-001",
    "reference_number": "INV-2024-001",
    "payer_name": "John Doe",
    "payer_email": "john@example.com",
    "description": "Payment for Bus Ticket",
    "created_at": "2024-11-13T10:00:00Z",
    "updated_at": "2024-11-13T12:00:00Z"
  }
}
```

---

### DELETE /api/payments/{id}
Hapus pembayaran.

**Path Parameters:**
- `id` (integer, required): ID pembayaran

**Example Request:**
```bash
curl -X DELETE "http://localhost:4000/payment/api/payments/1" \
  -H "Content-Type: application/json"
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Payment deleted successfully"
}
```

---

## Payment Methods

- **bank_transfer**: Transfer bank
- **credit_card**: Kartu kredit
- **e_wallet**: Dompet digital

---

## Payment Status

- **pending**: Menunggu konfirmasi
- **completed**: Pembayaran berhasil
- **failed**: Pembayaran gagal
- **refunded**: Pembayaran dikembalikan

---

**Last Updated:** November 2024
