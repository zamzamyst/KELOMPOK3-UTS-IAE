<?php
// Wajib load config.php untuk mendapatkan URL port
$cfg = [];
if (file_exists(__DIR__.'/config.php')) {
    $cfg = require_once __DIR__.'/config.php';
}

$gatewayUrl = defined('API_URL') ? API_URL : ($cfg['gateway'] ?? 'http://127.0.0.1:4000');
$gateway = rtrim($gatewayUrl, '/');

// --- LINK UI DIRECT (Untuk tombol "Open UI") ---
// Kita ambil dari config.php, default ke port langsung (8001, 8003, dst)
$busUi = $cfg['bus_direct'] ?? 'http://127.0.0.1:8001';
$ticketUi = $cfg['ticket_direct'] ?? 'http://127.0.0.1:8003';
$paymentUi = $cfg['payment_direct'] ?? 'http://127.0.0.1:8002';
$trackingUi = $cfg['tracking_direct'] ?? 'http://127.0.0.1:8004';

// --- LINK API VIA GATEWAY (Untuk Status Check/dot) ---
// Note: Endpoint API di microservice Laravel secara default memiliki prefix /api.
// Contoh: /api/bus-service akan diteruskan ke 8001/api
$busApi = $gateway . '/api/bus-service/buses';
$ticketApi = $gateway . '/api/ticket-service/tickets';
$paymentApi = $gateway . '/api/payment-service/payments';
$trackingApi = $gateway . '/api/tracking-service/trackings';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Service Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style> 
    .svc-card{min-height:140px} 
    .status-dot{width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:6px} 
    .online{background:#28a745} 
    .offline{background:#6c757d; opacity: 0.5;} 
    body { display: none; } /* Hide unauthenticated content */
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 border-bottom">
  <div class="container">
    <a class="navbar-brand" href="#">System Dashboard</a>
    <div class="d-flex align-items-center">
        <span class="navbar-text me-3 fw-bold text-primary" id="user-display"></span>
        <button onclick="logout()" class="btn btn-outline-danger btn-sm">Logout</button>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h1 class="mb-4">Microservices Dashboard</h1>
  <div class="alert alert-info small">
    Status dots are checked via <strong>API Gateway (Port 4000)</strong>.<br>
    "Open UI" buttons go directly to the Service Port to avoid URL/CSS issues.
  </div>

  <div class="row">
    <div class="col-md-3 mb-3">
      <div class="card svc-card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Bus Service</h5>
          <p class="card-text small text-muted">Manage buses, routes and schedules.</p>
          <div class="mt-auto">
            <div class="mb-2 d-flex align-items-center">
                <span id="bus-status" class="status-dot offline"></span>
                <small id="bus-text" class="text-muted">Checking...</small>
            </div>
            <a id="bus-link" href="<?= htmlspecialchars($busUi) ?>" target="_blank" class="btn btn-primary w-100">Open UI (8001)</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card svc-card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Ticket Service</h5>
          <p class="card-text small text-muted">Create and manage tickets.</p>
          <div class="mt-auto">
            <div class="mb-2 d-flex align-items-center">
                <span id="ticket-status" class="status-dot offline"></span>
                <small id="ticket-text" class="text-muted">Checking...</small>
            </div>
            <a id="ticket-link" href="<?= htmlspecialchars($ticketUi) ?>" target="_blank" class="btn btn-primary w-100">Open UI (8003)</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card svc-card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Payment Service</h5>
          <p class="card-text small text-muted">Process payments and view transactions.</p>
          <div class="mt-auto">
            <div class="mb-2 d-flex align-items-center">
                <span id="payment-status" class="status-dot offline"></span>
                <small id="payment-text" class="text-muted">Checking...</small>
            </div>
            <a id="payment-link" href="<?= htmlspecialchars($paymentUi) ?>" target="_blank" class="btn btn-primary w-100">Open UI (8002)</a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card svc-card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Tracking Service</h5>
          <p class="card-text small text-muted">View live bus tracking data.</p>
          <div class="mt-auto">
            <div class="mb-2 d-flex align-items-center">
                <span id="tracking-status" class="status-dot offline"></span>
                <small id="tracking-text" class="text-muted">Checking...</small>
            </div>
            <a id="tracking-link" href="<?= htmlspecialchars($trackingUi) ?>" target="_blank" class="btn btn-primary w-100">Open UI (8004)</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// 1. AUTH LOGIC (Checks token and redirects if necessary)
const token = localStorage.getItem('token');
if (!token) {
    window.location.href = 'login.php';
} else {
    document.body.style.display = 'block';
    const userStr = localStorage.getItem('user');
    if (userStr) {
        try {
            const user = JSON.parse(userStr);
            $('#user-display').text('Hi, ' + (user.name || 'User'));
        } catch(e) {}
    }
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = 'login.php';
}

// 2. SERVICE HEALTH CHECK (Via Gateway: Port 4000)
// Menggunakan endpoint API untuk cek status (sudah diperbaiki di Gateway)
const services = [
  {key:'bus', url: '<?= $busApi ?>'},
  {key:'ticket', url: '<?= $ticketApi ?>'},
  {key:'payment', url: '<?= $paymentApi ?>'},
  {key:'tracking', url: '<?= $trackingApi ?>'}
];

function checkService(s){
  $.ajax({
      url: s.url,
      method: 'GET',
      timeout: 5000,
      headers: { 'Authorization': 'Bearer ' + token }, 
      success: function() {
          setOnline(s.key);
      },
      error: function(xhr) {
          if (xhr.status === 401 || xhr.status === 403) {
             console.warn("Token expired for " + s.key);
             // JANGAN logout otomatis, cukup tandai offline/token expired
             setOffline(s.key, 'Token Expired');
          } else {
             setOffline(s.key, 'Offline');
          }
      }
  });
}

function setOnline(k){
  $('#'+k+'-status').removeClass('offline').addClass('online');
  $('#'+k+'-text').text('Online').addClass('text-success').removeClass('text-muted');
}
function setOffline(k, text = 'Offline'){
  $('#'+k+'-status').addClass('offline').removeClass('online');
  $('#'+k+'-text').text(text).removeClass('text-success').addClass('text-muted');
}

if (token) {
    services.forEach(s=> checkService(s));
}
</script>
</body>
</html>