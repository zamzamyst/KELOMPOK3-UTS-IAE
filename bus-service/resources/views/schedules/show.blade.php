@extends('layouts.app')
@section('title','Schedule Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Schedule Details</h1>
  <div>
    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this schedule?')">
      @csrf @method('DELETE')
      <button class="btn btn-danger">Delete</button>
    </form>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Schedule Information</h5>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="text-muted">Schedule ID</h6>
        <p class="mb-0">{{ $schedule->id }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Created At</h6>
        <p class="mb-0">{{ $schedule->created_at->format('d M Y H:i') }}</p>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Bus Information</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-muted">Bus Name</h6>
        <p class="mb-0">{{ $schedule->bus->name ?? 'N/A' }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Plate Number</h6>
        <p class="mb-0">{{ $schedule->bus->plate_number ?? 'N/A' }}</p>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <h6 class="text-muted">Capacity</h6>
        <p class="mb-0">{{ $schedule->bus->capacity ?? 'N/A' }} seats</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Bus Type</h6>
        <p class="mb-0">{{ $schedule->bus->type ?? 'N/A' }}</p>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Route Information</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-muted">Route Code</h6>
        <p class="mb-0">{{ $schedule->route->code ?? 'N/A' }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Route Path</h6>
        <p class="mb-0">{{ $schedule->route->origin ?? 'N/A' }} → {{ $schedule->route->destination ?? 'N/A' }}</p>
      </div>
    </div>
    @if($schedule->route->stops && count($schedule->route->stops) > 0)
    <div class="row mt-3">
      <div class="col-md-12">
        <h6 class="text-muted">Stops</h6>
        <div class="alert alert-light border">
          @foreach($schedule->route->stops as $stop)
            <span class="badge bg-info">{{ $stop }}</span>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Schedule Details</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-muted">Departure Time</h6>
        <p class="mb-0 font-monospace">{{ $schedule->departure_at }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Arrival Time</h6>
        <p class="mb-0 font-monospace">{{ $schedule->arrival_at ?? '-' }}</p>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <h6 class="text-muted">Available Seats</h6>
        <p class="mb-0">
          <span class="badge bg-success">{{ $schedule->available_seats }}</span>
        </p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Price</h6>
        <p class="mb-0 h5">Rp{{ number_format($schedule->price, 0, ',', '.') }}</p>
      </div>
    </div>
  </div>
</div>

<div class="mt-3">
  <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Back to Schedules</a>
</div>
@endsection
