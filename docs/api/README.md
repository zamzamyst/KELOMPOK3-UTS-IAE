# API Documentation - BusTrack Hub

Dokumentasi lengkap API untuk sistem BusTrack Hub microservices.

## 🔗 Quick Links

- [Main README](../../README.md)
- [Bus Service Details](bus-service.md)
- [Ticket Service Details](ticket-service.md)
- [Payment Service Details](payment-service.md)
- [Tracking Service Details](tracking-service.md)

---

## 🔗 Base URLs

| Service | URL | Port |
|---------|-----|------|
| **API Gateway** | `http://localhost:4000` | 4000 |
| **Bus Service** | `http://localhost:8001` | 8001 |
| **Ticket Service** | `http://localhost:8003` | 8003 |
| **Payment Service** | `http://localhost:8002` | 8002 |
| **Tracking Service** | `http://localhost:8004` | 8004 |
| **Dashboard (Frontend)** | `http://localhost:8000` | 8000 |

---

## ⚠️ Error Handling

### Error Response Format

Semua error response mengikuti format ini:

```json
{
  "success": false,
  "message": "Error message",
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

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

---

**Last Updated:** November 2025
**Version:** 1.0
