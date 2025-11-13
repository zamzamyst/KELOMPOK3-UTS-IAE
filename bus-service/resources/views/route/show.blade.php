@extends('layouts.app')
@section('title','Route Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Route Details</h1>
  <div>
    <a href="{{ route('routes.edit', $route) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('routes.destroy', $route) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this route?')">
      @csrf @method('DELETE')
      <button class="btn btn-danger">Delete</button>
    </form>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="text-muted">Route Code</h6>
        <p class="mb-0">{{ $route->code }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Created At</h6>
        <p class="mb-0">{{ $route->created_at->format('d M Y H:i') }}</p>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="text-muted">Origin</h6>
        <p class="mb-0">{{ $route->origin }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Destination</h6>
        <p class="mb-0">{{ $route->destination }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h6 class="text-muted">Stops</h6>
        @if($route->stops && count($route->stops) > 0)
          <div class="alert alert-light border">
            @foreach($route->stops as $index => $stop)
              <span class="badge bg-info">{{ $stop }}</span>
              @if(!$loop->last)
                <span class="mx-2">→</span>
              @endif
            @endforeach
          </div>
        @else
          <p class="text-muted mb-0">No stops defined</p>
        @endif
      </div>
    </div>
  </div>
</div>

<h5>Related Schedules</h5>
@if($route->schedules->count() > 0)
  <table class="table table-sm table-striped">
    <thead>
      <tr>
        <th>Schedule ID</th>
        <th>Bus</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Available Seats</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
      @foreach($route->schedules as $schedule)
      <tr>
        <td>{{ $schedule->id }}</td>
        <td>{{ $schedule->bus->name ?? 'N/A' }}</td>
        <td>{{ $schedule->departure_at }}</td>
        <td>{{ $schedule->arrival_at ?? '-' }}</td>
        <td>{{ $schedule->available_seats }}</td>
        <td>Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="text-muted">No schedules yet for this route.</p>
@endif

<div class="mt-3">
  <a href="{{ route('routes.index') }}" class="btn btn-secondary">Back to Routes</a>
</div>
@endsection
