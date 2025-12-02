# API Gateway - JWT Authentication

## Setup

1. Install dependencies:
```bash
npm install
```

2. Configure JWT secret in `.env`:
```
JWT_SECRET=your_secret_key_here
JWT_EXPIRY=24h
```

## Usage

### 1. Login to get JWT Token

**Request:**
```bash
POST http://localhost:4000/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "any_password"
}
```

**Response:**
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "message": "Login successful"
}
```

### 2. Access Services with Token

All microservice endpoints require the JWT token in the Authorization header:

```bash
GET http://localhost:4000/api/bus-service/buses
Authorization: Bearer <your_token_here>
```

**Example using curl:**
```bash
curl -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  http://localhost:4000/api/bus-service/buses
```

### 3. Available Protected Services

All services are protected behind JWT authentication:
- `/api/bus-service/*` - Bus Service
- `/api/ticket-service/*` - Ticket Service
- `/api/payment-service/*` - Payment Service
- `/api/tracking-service/*` - Tracking Service

### 4. Public Endpoints

These endpoints don't require authentication:
- `POST /auth/login` - Get JWT token
- `GET /health` - Health check

## How It Works

1. Client sends email/password to `/auth/login`
2. Gateway generates JWT token and returns it
3. Client includes token in `Authorization: Bearer <token>` header
4. Gateway validates token before forwarding requests to microservices
5. All microservices receive the request as-is (no auth needed in each service)

## Environment Variables

- `JWT_SECRET` - Secret key for signing tokens (change in production!)
- `JWT_EXPIRY` - Token expiration time (default: 24h)

## Notes

- Token validation happens at the gateway level only
- Microservices don't need to handle authentication
- Update login endpoint to verify credentials against your actual user database
