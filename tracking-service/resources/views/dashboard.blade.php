@extends('layouts.app')
@section('title','Service Dashboard')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Bus Service</h5>
        <p class="card-text">Manage buses, routes and schedules.</p>
        <a href="{{ env('BUS_SERVICE_URL','http://127.0.0.1:8001') }}" class="btn btn-primary">Open</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Ticket Service</h5>
        <p class="card-text">Create and manage tickets.</p>
        <a href="{{ env('TICKET_SERVICE_URL','http://127.0.0.1:8003') }}" class="btn btn-primary">Open</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Payment Service</h5>
        <p class="card-text">Process payments and view transactions.</p>
        <a href="{{ env('PAYMENT_SERVICE_URL','http://127.0.0.1:8002') }}" class="btn btn-primary">Open</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Tracking Service</h5>
        <p class="card-text">View live bus tracking data.</p>
        <a href="{{ env('TRACKING_SERVICE_URL','http://127.0.0.1:8004') }}" class="btn btn-primary">Open</a>
      </div>
    </div>
  </div>
</div>

@endsection
