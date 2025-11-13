# Bus Service - Dokumentasi Komprehensif

## 📋 Daftar Isi
- [Pengenalan](#pengenalan)
- [Teknologi & Dependencies](#teknologi--dependencies)
- [Struktur Proyek](#struktur-proyek)
- [Setup & Instalasi](#setup--instalasi)
- [Database](#database)
- [API Endpoints](#api-endpoints)
- [Web Routes & Views](#web-routes--views)
- [Models & Relationships](#models--relationships)
- [Controllers](#controllers)
- [Validasi & Error Handling](#validasi--error-handling)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Pengenalan

Bus Service adalah microservice yang menangani manajemen **bus**, **rute**, dan **jadwal** dalam sistem transportasi. Service ini menyediakan:

- **REST API** untuk integrasi dengan service lain (terutama ticket-service)
- **Web Admin Panel** untuk manajemen data bus, rute, dan jadwal
- **Validasi & Conflict Detection** untuk cegah bentrok jadwal
- **Relationship Management** antara bus, rute, dan jadwal

### Fitur Utama
✅ CRUD Bus (Plate Number, Name, Capacity, Type)  
✅ CRUD Routes (Code, Origin, Destination, Stops)  
✅ CRUD Schedules (Bus, Route, Departure, Arrival, Seats, Price)  
✅ Bus-Route Assignment  
✅ Schedule Conflict Detection  
✅ Web Admin Interface (Bootstrap 5)  
✅ REST API dengan Response JSON  

---

## 🛠️ Teknologi & Dependencies

### Framework & Language
- **PHP**: 8.0+
- **Laravel**: 9.x
- **Node.js**: 18+ (untuk Vite)

### Key Packages
```json
{
  "require": {
    "laravel/framework": "^9.0",
    "laravel/sanctum": "^2.14.1",
    "laravel/tinker": "^2.7"
  }
}
```

### Frontend
- **Bootstrap**: 5.3.0 (CSS via CDN)
- **Blade**: Laravel's templating engine

### Database
- **MySQL**: 8.0+
- **Migrations**: Laravel migrations

---

## 📁 Struktur Proyek

```
bus-service/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BusController.php              (API)
│   │   │   ├── BusRouteController.php         (API)
│   │   │   ├── ScheduleController.php         (API)
│   │   │   ├── InternalController.php         (API Internal)
│   │   │   └── Web/
│   │   │       ├── BusAdminController.php      (Web UI)
│   │   │       ├── RouteAdminController.php    (Web UI)
│   │   │       └── ScheduleAdminController.php (Web UI)
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Bus.php
│   │   ├── BusRoute.php
│   │   └── Schedule.php
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── *_create_buses_table.php
│   │   ├── *_create_routes_table.php
│   │   ├── *_create_schedules_table.php
│   │   └── *_create_bus_route_table.php
│   ├── factories/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── buses/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── route/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── schedules/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php
├── routes/
│   ├── api.php                  (REST API Routes)
│   └── web.php                  (Web Admin Routes)
├── config/
│   ├── app.php
│   ├── database.php
│   └── services.php
├── public/
│   └── index.php
└── README_DOKUMENTASI.md        (File ini)
```

---

## 🚀 Setup & Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd bus-service
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment File
```bash
cp .env.example .env
```

Edit `.env`:
```env
APP_NAME="Bus Service"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bus_service
DB_USERNAME=root
DB_PASSWORD=

API_GATEWAY_URL=http://127.0.0.1:4000
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Create Database
```bash
mysql -u root -p
CREATE DATABASE bus_service;
exit;
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. (Optional) Seed Database
```bash
php artisan db:seed
```

### 8. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 9. Start Development Server
```bash
php artisan serve --port=8001
```

Server akan berjalan di: `http://127.0.0.1:8001`

---

## 🗄️ Database

### Tables & Schemas

#### `buses`
| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| id | bigint | ✗ | Primary key |
| plate_number | varchar(50) | ✗ | Nomor plat unik |
| name | varchar(255) | ✗ | Nama bus |
| capacity | integer | ✗ | Kapasitas penumpang |
| type | varchar(100) | ✓ | Tipe bus (AC, Non-AC, dll) |
| created_at | timestamp | ✗ | - |
| updated_at | timestamp | ✗ | - |

#### `routes`
| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| id | bigint | ✗ | Primary key |
| code | varchar(50) | ✗ | Kode rute unik |
| origin | varchar(255) | ✗ | Kota asal |
| destination | varchar(255) | ✗ | Kota tujuan |
| stops | json | ✓ | Kota-kota pemberhentian |
| created_at | timestamp | ✗ | - |
| updated_at | timestamp | ✗ | - |

#### `schedules`
| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| id | bigint | ✗ | Primary key |
| bus_id | bigint | ✗ | Foreign key ke buses |
| route_id | bigint | ✗ | Foreign key ke routes |
| departure_at | datetime | ✗ | Waktu keberangkatan |
| arrival_at | datetime | ✓ | Waktu kedatangan |
| available_seats | integer | ✗ | Kursi tersedia |
| price | decimal(10,2) | ✗ | Harga tiket |
| created_at | timestamp | ✗ | - |
| updated_at | timestamp | ✗ | - |

#### `bus_route` (Pivot Table)
| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| bus_id | bigint | ✗ | Foreign key ke buses |
| route_id | bigint | ✗ | Foreign key ke routes |

---

## 🔌 API Endpoints

### Base URL
```
http://127.0.0.1:8001/api
```

### Authentication
Endpoints API saat ini **tidak memerlukan** token authentication. Untuk production, tambahkan:
- Laravel Sanctum
- JWT Token
- API Key validation

---

### Bus Endpoints

#### GET /api/buses
Retrieve all buses
```bash
curl http://127.0.0.1:8001/api/buses
```

**Response:**
```json
[
  {
    "id": 1,
    "plate_number": "B1234AB",
    "name": "Bus Express 01",
    "capacity": 40,
    "type": "AC",
    "created_at": "2024-01-10T10:00:00",
    "updated_at": "2024-01-10T10:00:00"
  }
]
```

#### POST /api/buses
Create a new bus
```bash
curl -X POST http://127.0.0.1:8001/api/buses \
  -H "Content-Type: application/json" \
  -d '{
    "plate_number": "B1234AB",
    "name": "Bus Express 01",
    "capacity": 40,
    "type": "AC"
  }'
```

#### GET /api/buses/{id}
Get specific bus
```bash
curl http://127.0.0.1:8001/api/buses/1
```

#### PUT /api/buses/{id}
Update bus
```bash
curl -X PUT http://127.0.0.1:8001/api/buses/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bus Express Updated",
    "capacity": 45
  }'
```

#### DELETE /api/buses/{id}
Delete bus
```bash
curl -X DELETE http://127.0.0.1:8001/api/buses/1
```

---

### Route Endpoints

#### GET /api/routes
Retrieve all routes
```bash
curl http://127.0.0.1:8001/api/routes
```

**Response:**
```json
[
  {
    "id": 1,
    "code": "RT001",
    "origin": "Jakarta",
    "destination": "Bandung",
    "stops": ["Bogor", "Ciawi"],
    "created_at": "2024-01-10T10:00:00",
    "updated_at": "2024-01-10T10:00:00"
  }
]
```

#### POST /api/routes
Create a new route
```bash
curl -X POST http://127.0.0.1:8001/api/routes \
  -H "Content-Type: application/json" \
  -d '{
    "code": "RT001",
    "origin": "Jakarta",
    "destination": "Bandung",
    "stops": ["Bogor", "Ciawi"]
  }'
```

#### GET /api/routes/{id}
Get specific route
```bash
curl http://127.0.0.1:8001/api/routes/1
```

#### PUT /api/routes/{id}
Update route
```bash
curl -X PUT http://127.0.0.1:8001/api/routes/1 \
  -H "Content-Type: application/json" \
  -d '{
    "destination": "Yogyakarta",
    "stops": ["Bogor", "Bandung", "Garut"]
  }'
```

#### DELETE /api/routes/{id}
Delete route
```bash
curl -X DELETE http://127.0.0.1:8001/api/routes/1
```

#### POST /api/routes/{id}/assign-bus
Assign bus to route
```bash
curl -X POST http://127.0.0.1:8001/api/routes/1/assign-bus \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 1
  }'
```

---

### Schedule Endpoints

#### GET /api/schedules
Retrieve all schedules with relationships
```bash
curl http://127.0.0.1:8001/api/schedules
```

**Response:**
```json
[
  {
    "id": 1,
    "bus_id": 1,
    "route_id": 1,
    "departure_at": "2024-01-15 08:00:00",
    "arrival_at": "2024-01-15 10:30:00",
    "available_seats": 35,
    "price": "150000.00",
    "bus": {
      "id": 1,
      "plate_number": "B1234AB",
      "name": "Bus Express 01",
      "capacity": 40,
      "type": "AC"
    },
    "route": {
      "id": 1,
      "code": "RT001",
      "origin": "Jakarta",
      "destination": "Bandung"
    }
  }
]
```

#### POST /api/schedules
Create a new schedule
```bash
curl -X POST http://127.0.0.1:8001/api/schedules \
  -H "Content-Type: application/json" \
  -d '{
    "bus_id": 1,
    "route_id": 1,
    "departure_at": "2024-01-15 08:00:00",
    "arrival_at": "2024-01-15 10:30:00",
    "available_seats": 40,
    "price": 150000
  }'
```

**Validasi:**
- Bus harus assignment ke route yang sama
- Tidak boleh ada schedule conflict untuk bus yang sama
- `available_seats` harus positif
- `arrival_at` harus >= `departure_at`

#### GET /api/schedules/{id}
Get specific schedule
```bash
curl http://127.0.0.1:8001/api/schedules/1
```

#### PUT /api/schedules/{id}
Update schedule
```bash
curl -X PUT http://127.0.0.1:8001/api/schedules/1 \
  -H "Content-Type: application/json" \
  -d '{
    "available_seats": 30,
    "price": 160000
  }'
```

#### DELETE /api/schedules/{id}
Delete schedule
```bash
curl -X DELETE http://127.0.0.1:8001/api/schedules/1
```

---

### Internal Endpoints (untuk ticket-service)

#### GET /api/internal/schedules/{id}
Get schedule details (untuk reserve seats)
```bash
curl http://127.0.0.1:8001/api/internal/schedules/1
```

#### PUT /api/internal/schedules/{id}/reserve
Reserve seats pada schedule
```bash
curl -X PUT http://127.0.0.1:8001/api/internal/schedules/1/reserve \
  -H "Content-Type: application/json" \
  -d '{
    "seats": 2
  }'
```

---

## 🌐 Web Routes & Views

### Routes Definition (`routes/web.php`)

```php
Route::resource('buses', BusAdminController::class);
Route::resource('routes', RouteAdminController::class);
Route::resource('schedules', ScheduleAdminController::class);
```

### Available Routes

| Method | Route | View | Controller Method | Description |
|--------|-------|------|-------------------|-------------|
| GET | `/buses` | buses.index | index() | List all buses |
| GET | `/buses/create` | buses.create | create() | Form create bus |
| POST | `/buses` | - | store() | Save new bus |
| GET | `/buses/{id}` | buses.show | show() | Bus details |
| GET | `/buses/{id}/edit` | buses.edit | edit() | Form edit bus |
| PUT | `/buses/{id}` | - | update() | Update bus |
| DELETE | `/buses/{id}` | - | destroy() | Delete bus |
| GET | `/routes` | route.index | index() | List all routes |
| GET | `/routes/create` | route.create | create() | Form create route |
| POST | `/routes` | - | store() | Save new route |
| GET | `/routes/{id}` | route.show | show() | Route details |
| GET | `/routes/{id}/edit` | route.edit | edit() | Form edit route |
| PUT | `/routes/{id}` | - | update() | Update route |
| DELETE | `/routes/{id}` | - | destroy() | Delete route |
| GET | `/schedules` | schedules.index | index() | List all schedules |
| GET | `/schedules/create` | schedules.create | create() | Form create schedule |
| POST | `/schedules` | - | store() | Save new schedule |
| GET | `/schedules/{id}` | schedules.show | show() | Schedule details |
| GET | `/schedules/{id}/edit` | schedules.edit | edit() | Form edit schedule |
| PUT | `/schedules/{id}` | - | update() | Update schedule |
| DELETE | `/schedules/{id}` | - | destroy() | Delete schedule |

### Web UI Access

- **Buses Management**: `http://127.0.0.1:8001/buses`
- **Routes Management**: `http://127.0.0.1:8001/routes`
- **Schedules Management**: `http://127.0.0.1:8001/schedules`

---

## 🏗️ Models & Relationships

### Bus Model
```php
class Bus extends Model {
    protected $fillable = ['plate_number', 'name', 'capacity', 'type'];
    
    public function schedules() { 
        return $this->hasMany(Schedule::class); 
    }
    
    public function routes() {
        return $this->belongsToMany(BusRoute::class, 'bus_route');
    }
}
```

**Relationships:**
- `Bus -> has many -> Schedules`
- `Bus -> many-to-many -> Routes` (via `bus_route` pivot table)

---

### BusRoute Model
```php
class BusRoute extends Model {
    protected $table = 'routes';
    protected $fillable = ['code', 'origin', 'destination', 'stops'];
    protected $casts = ['stops' => 'array'];
    
    public function schedules() { 
        return $this->hasMany(Schedule::class, 'route_id'); 
    }

    public function buses() {
        return $this->belongsToMany(Bus::class, 'bus_route');
    }
}
```

**Relationships:**
- `Route -> has many -> Schedules`
- `Route -> many-to-many -> Buses` (via `bus_route` pivot table)

---

### Schedule Model
```php
class Schedule extends Model {
    protected $fillable = [
        'bus_id', 'route_id', 'departure_at', 
        'arrival_at', 'available_seats', 'price'
    ];

    public function bus() { 
        return $this->belongsTo(Bus::class); 
    }

    public function route() { 
        return $this->belongsTo(BusRoute::class, 'route_id'); 
    }
}
```

**Relationships:**
- `Schedule -> belongs to -> Bus`
- `Schedule -> belongs to -> Route`

---

## 👨‍💼 Controllers

### API Controllers

#### BusController (`app/Http/Controllers/BusController.php`)
```php
public function index()          // GET /api/buses
public function store()          // POST /api/buses
public function show(Bus $bus)   // GET /api/buses/{id}
public function update()         // PUT /api/buses/{id}
public function destroy()        // DELETE /api/buses/{id}
```

#### BusRouteController
```php
public function index()                           // GET /api/routes
public function store()                           // POST /api/routes
public function show(BusRoute $route)             // GET /api/routes/{id}
public function update()                          // PUT /api/routes/{id}
public function destroy()                         // DELETE /api/routes/{id}
public function assignBus()                       // POST /api/routes/{id}/assign-bus
```

**Features:**
- Automatic JSON encoding of `stops` array
- Bus assignment validation
- Conflict prevention

#### ScheduleController
```php
public function index()                 // GET /api/schedules (with relationships)
public function store()                 // POST /api/schedules
public function show(Schedule $schedule) // GET /api/schedules/{id}
public function update()                // PUT /api/schedules/{id}
public function destroy()               // DELETE /api/schedules/{id}
```

**Features:**
- Schedule conflict detection
- Bus-Route assignment validation
- Auto-selection of bus if not provided
- Datetime overlap checking

#### InternalController
```php
public function getSchedule($id)        // GET /api/internal/schedules/{id}
public function reserveSeats()          // PUT /api/internal/schedules/{id}/reserve
```

**Used by:** Ticket Service untuk reserve seats dan cek ketersediaan.

---

### Web Controllers

#### BusAdminController (`app/Http/Controllers/Web/BusAdminController.php`)
Menangani form-based CRUD untuk buses dengan redirect dan flash messages.

#### RouteAdminController (`app/Http/Controllers/Web/RouteAdminController.php`)
Menangani form-based CRUD untuk routes dengan:
- Stops string-to-array conversion
- Form validation
- Session messaging

#### ScheduleAdminController (`app/Http/Controllers/Web/ScheduleAdminController.php`)
Menangani form-based CRUD untuk schedules dengan:
- Multiple relationships (bus, route)
- Datetime validation
- Session messaging

---

## ✅ Validasi & Error Handling

### Bus Validation
```php
'plate_number' => 'required|unique:buses',
'name' => 'required',
'capacity' => 'required|integer|min:1',
'type' => 'nullable'
```

### Route Validation
```php
'code' => 'required|unique:routes,code,'.$route->id,
'origin' => 'required',
'destination' => 'required',
'stops' => 'nullable|array' // atau 'nullable|string' untuk form input
```

### Schedule Validation
```php
'bus_id' => 'required|exists:buses,id',
'route_id' => 'required|exists:routes,id',
'departure_at' => 'required|date',
'arrival_at' => 'nullable|date|after_or_equal:departure_at',
'available_seats' => 'required|integer|min:1',
'price' => 'required|numeric|min:0'
```

### Schedule Conflict Detection
```php
// Cek overlap antara schedule yang ada
if (strtotime($exDep) < strtotime($newArr) && 
    strtotime($exArr) > strtotime($departure)) {
    return response()->json([
        'message' => 'Schedule conflict: bus has another schedule overlapping'
    ], 422);
}
```

### Error Response Examples

**Validation Error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "plate_number": ["The plate number has already been taken."],
    "capacity": ["The capacity must be an integer."]
  }
}
```

**Not Found (404):**
```json
{
  "message": "Not found"
}
```

**Conflict (422):**
```json
{
  "message": "Bus is already assigned to another route"
}
```

---

## 🎯 Best Practices

### 1. Always Include Relationships
```php
// ✓ Good
$schedules = Schedule::with(['bus', 'route'])->get();

// ✗ Avoid
$schedules = Schedule::all();
```

### 2. Use Model Binding
```php
// ✓ Good - Laravel auto-fetch dari ID
public function show(Schedule $schedule) {
    return view('schedules.show', compact('schedule'));
}

// ✗ Avoid - Manual query
public function show($id) {
    $schedule = Schedule::find($id);
}
```

### 3. Consistent Error Messages
```php
// ✓ Good
return redirect()->route('buses.index')->with('success', 'Bus created successfully');

// ✓ Also Good
return response()->json(['message' => 'Bus created successfully'], 201);
```

### 4. Validate Before Processing
```php
$validated = $request->validate([...]);
// Use $validated, tidak perlu $request->input()
```

### 5. Use Transactions untuk Multi-Step Operations
```php
DB::transaction(function () {
    // Assign bus to route
    // Create schedule
    // Update available seats
});
```

### 6. Document API Responses
```php
/**
 * Get all schedules with relationships
 * 
 * @return \Illuminate\Http\JsonResponse
 * @example GET /api/schedules
 * @response {
 *   "id": 1,
 *   "bus": {...},
 *   "route": {...}
 * }
 */
public function index()
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. **"SQLSTATE[HY000]: General error: 1025"**
Problem: Migration error saat create constraints

**Solution:**
```bash
# Drop all tables dan restart migrations
php artisan migrate:refresh
```

#### 2. **"Column not found in database"**
Problem: Model properties tidak match dengan database columns

**Solution:**
```bash
# Check migration file
php artisan migrate:status

# Rollback dan re-run specific migration
php artisan migrate:rollback --step=1
php artisan migrate
```

#### 3. **"CORS Error" saat API Call**
Problem: Frontend tidak bisa akses API dari domain berbeda

**Solution:**
Setup CORS di `config/cors.php`:
```php
'allowed_origins' => ['http://127.0.0.1:8000', 'http://localhost:3000'],
```

#### 4. **"Class not found" Error**
Problem: Controller/Model tidak bisa di-load

**Solution:**
```bash
# Regenerate autoload
composer dump-autoload

# Clear config
php artisan config:clear
php artisan cache:clear
```

#### 5. **Schedule Conflict False Positive**
Problem: Overlap detection terlalu strict

**Solution:**
Review logic di `ScheduleController@store()`:
```php
// Pastikan waktu dibandingkan dengan format datetime yang konsisten
if (strtotime($exDep) < strtotime($newArr) && 
    strtotime($exArr) > strtotime($departure)) {
    // Overlap detected
}
```

#### 6. **"Route not found" (404)**
Problem: Route tidak ter-register

**Solution:**
```bash
# List all routes
php artisan route:list

# Pastikan route defined di routes/web.php atau routes/api.php
# Check namespace di controller
```

#### 7. **Validation Error tidak Muncul di View**
Problem: Error messages tidak ditampilkan

**Solution:**
```blade
<!-- Pastikan menggunakan @error directive -->
@error('field_name')
  <div class="text-danger">{{ $message }}</div>
@enderror
```

#### 8. **Database Connection Error**
Problem: Tidak bisa connect ke MySQL

**Solution:**
```bash
# Check .env database config
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bus_service
DB_USERNAME=root
DB_PASSWORD=

# Test connection
php artisan tinker
>>> DB::connection()->getPDO();
```

---

## 📞 Integration dengan Service Lain

### Ticket Service Integration

**Ticket Service** menggunakan 2 endpoint untuk manage reservations:

1. **Get Schedule Details**
```php
GET /api/internal/schedules/{id}
```

2. **Reserve Seats**
```php
PUT /api/internal/schedules/{id}/reserve
Body: { "seats": 2 }
```

**Flow:**
```
Ticket Service
    ↓
GET /api/internal/schedules/1  (cek ketersediaan)
    ↓
PUT /api/internal/schedules/1/reserve  (kurangi available_seats)
    ↓
Bus Service
```

---

## 📊 Database Query Examples

### Cek Jadwal Bus Tertentu
```php
$schedules = Schedule::where('bus_id', 1)
    ->with('route')
    ->orderBy('departure_at')
    ->get();
```

### Cek Rute yang Memiliki Jadwal
```php
$routes = BusRoute::whereHas('schedules')
    ->withCount('schedules')
    ->get();
```

### Cek Bus yang Belum di-assign
```php
$unassignedBuses = Bus::doesntHave('routes')
    ->get();
```

### Jadwal dalam 7 Hari ke Depan
```php
$upcomingSchedules = Schedule::whereBetween('departure_at', [
    now(),
    now()->addDays(7)
])->get();
```

---

## 🚄 Performance Tips

1. **Eager Load Relationships**
```php
Schedule::with('bus', 'route')->get();  // 1 query
// vs
Schedule::all();  // N+1 queries
```

2. **Use Select untuk Specific Columns**
```php
Bus::select('id', 'name', 'capacity')->get();
```

3. **Pagination untuk Large Data**
```php
$schedules = Schedule::paginate(15);
```

4. **Cache untuk Data yang Jarang Berubah**
```php
$routes = Cache::remember('routes', 60, function () {
    return BusRoute::all();
});
```

5. **Use Database Indexes**
```php
// Di migration
$table->index('plate_number');
$table->unique('code');
```

---

## 📝 Changelog

### Version 1.0.0
- ✅ Initial release
- ✅ CRUD Bus, Route, Schedule
- ✅ REST API endpoints
- ✅ Web Admin Panel
- ✅ Schedule conflict detection
- ✅ Bus-Route assignment

---

## 📄 License

This project is part of **KELOMPOK3-UTS-IAE** academic project.

---

## 👥 Contributors

- **Team KELOMPOK3**
- Branch: `fathur`

---

## 📧 Support & Questions

Untuk questions atau issues, silakan buat issue di repository atau hubungi development team.

---

**Last Updated:** November 13, 2024  
**Documentation Version:** 1.0.0
