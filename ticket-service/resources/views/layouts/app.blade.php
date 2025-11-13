<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Ticket Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @php $gateway = rtrim(env('API_GATEWAY_URL','http://127.0.0.1:4000'), '/'); @endphp
        <li class="nav-item"><a class="nav-link" href="{{ $gateway.'/bus-service/buses' }}">Bus Service</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ $gateway.'/ticket-service/tickets' }}">Ticket Service</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ $gateway.'/payment-service/payments' }}">Payment Service</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ $gateway.'/tracking-service/trackings' }}">Tracking Service</a></li>
      </ul>
      <form class="d-flex" role="search" action="{{ url('/') }}" method="GET">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="q">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
