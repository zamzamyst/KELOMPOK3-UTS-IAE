@extends('layouts.app')
@section('title','Bus Detail')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Bus #{{ $bus->id }} - {{ $bus->name }}</h1>
  <div>
    <a href="{{ route('buses.edit', $bus) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('buses.destroy', $bus) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this bus?')">
      @csrf @method('DELETE')
      <button class="btn btn-danger">Delete</button>
    </form>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Bus Information</h5>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="text-muted">Plate Number</h6>
        <p class="mb-0">{{ $bus->plate_number }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Bus Name</h6>
        <p class="mb-0">{{ $bus->name }}</p>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="text-muted">Capacity</h6>
        <p class="mb-0">{{ $bus->capacity }} seats</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Bus Type</h6>
        <p class="mb-0">{{ $bus->type ?? 'N/A' }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-muted">Created At</h6>
        <p class="mb-0">{{ $bus->created_at->format('d M Y H:i') }}</p>
      </div>
      <div class="col-md-6">
        <h6 class="text-muted">Last Updated</h6>
        <p class="mb-0">{{ $bus->updated_at->format('d M Y H:i') }}</p>
      </div>
    </div>
  </div>
</div>

<h5>Related Schedules</h5>
@if($bus->schedules->count() > 0)
  <table class="table table-sm table-striped">
    <thead>
      <tr>
        <th>Schedule ID</th>
        <th>Route</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Available Seats</th>
        <th>Price</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bus->schedules as $schedule)
      <tr>
        <td>{{ $schedule->id }}</td>
        <td>{{ $schedule->route->code ?? 'N/A' }} - {{ $schedule->route->origin ?? 'N/A' }} → {{ $schedule->route->destination ?? 'N/A' }}</td>
        <td>{{ $schedule->departure_at }}</td>
        <td>{{ $schedule->arrival_at ?? '-' }}</td>
        <td>{{ $schedule->available_seats }}</td>
        <td>Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="text-muted">No schedules yet for this bus.</p>
@endif

<div class="mt-3">
  <a href="{{ route('buses.index') }}" class="btn btn-secondary">Back to Buses</a>
</div>
@endsection
