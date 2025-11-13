<?php
$cfg = [];
if (file_exists(__DIR__.'/config.php')) {
    $cfg = include __DIR__.'/config.php';
}
$gateway = rtrim($cfg['gateway'] ?? 'http://127.0.0.1:4000', '/');
$bus = $gateway . '/bus-service/buses';
$ticket = $gateway . '/ticket-service/tickets';
$payment = $gateway . '/payment-service/payments';
$tracking = $gateway . '/tracking-service/trackings';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Service Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style> .svc-card{min-height:140px} .status-dot{width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:6px} .online{background:#28a745} .offline{background:#6c757d} </style>
</head>
<body>
<div class="container py-4">
  <h1 class="mb-4">Microservices Dashboard</h1>
  <p class="text-muted">This dashboard runs independently on port 8000 and links to each service's port.</p>

  <div class="row">
    <div class="col-md-3">
      <div class="card svc-card">
        <div class="card-body">
          <h5 class="card-title">Bus Service</h5>
          <p class="card-text">Manage buses, routes and schedules.</p>
          <div class="mb-2"><span id="bus-status" class="status-dot offline"></span><small id="bus-text">unknown</small></div>
          <a id="bus-link" href="<?= htmlspecialchars($bus) ?>" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card svc-card">
        <div class="card-body">
          <h5 class="card-title">Ticket Service</h5>
          <p class="card-text">Create and manage tickets.</p>
          <div class="mb-2"><span id="ticket-status" class="status-dot offline"></span><small id="ticket-text">unknown</small></div>
          <a id="ticket-link" href="<?= htmlspecialchars($ticket) ?>" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card svc-card">
        <div class="card-body">
          <h5 class="card-title">Payment Service</h5>
          <p class="card-text">Process payments and view transactions.</p>
          <div class="mb-2"><span id="payment-status" class="status-dot offline"></span><small id="payment-text">unknown</small></div>
          <a id="payment-link" href="<?= htmlspecialchars($payment) ?>" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card svc-card">
        <div class="card-body">
          <h5 class="card-title">Tracking Service</h5>
          <p class="card-text">View live bus tracking data.</p>
          <div class="mb-2"><span id="tracking-status" class="status-dot offline"></span><small id="tracking-text">unknown</small></div>
          <a id="tracking-link" href="<?= htmlspecialchars($tracking) ?>" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const services = [
  {key:'bus', url: <?= json_encode($bus) ?>},
  {key:'ticket', url: <?= json_encode($ticket) ?>},
  {key:'payment', url: <?= json_encode($payment) ?>},
  {key:'tracking', url: <?= json_encode($tracking) ?>}
];

function checkService(s){
  fetch(s.url, {method:'GET', mode:'no-cors'}).then(()=>{
    setOnline(s.key);
  }).catch(()=>{
    setOffline(s.key);
  });
}

function setOnline(k){
  const dot = document.getElementById(k+'-status');
  const txt = document.getElementById(k+'-text');
  if(dot){ dot.classList.remove('offline'); dot.classList.add('online'); }
  if(txt) txt.textContent = 'online';
}
function setOffline(k){
  const dot = document.getElementById(k+'-status');
  const txt = document.getElementById(k+'-text');
  if(dot){ dot.classList.remove('online'); dot.classList.add('offline'); }
  if(txt) txt.textContent = 'offline';
}

services.forEach(s=> checkService(s));
</script>
</body>
</html>
