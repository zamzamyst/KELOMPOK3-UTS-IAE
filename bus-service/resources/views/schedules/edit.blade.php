@extends('layouts.app')
@section('title','Edit Schedule')
@section('content')
<h1>Edit Schedule</h1>
<form method="POST" action="{{ route('schedules.update', $schedule) }}">
  @csrf @method('PUT')
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Bus</label>
      <select name="bus_id" class="form-control" required>
        <option value="">-- Select Bus --</option>
        @foreach($buses as $bus)
          <option value="{{ $bus->id }}" {{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}>
            {{ $bus->name }} ({{ $bus->plate_number }}) - Capacity: {{ $bus->capacity }}
          </option>
        @endforeach
      </select>
      @error('bus_id')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Route</label>
      <select name="route_id" class="form-control" required>
        <option value="">-- Select Route --</option>
        @foreach($routes as $route)
          <option value="{{ $route->id }}" {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
            {{ $route->code }} - {{ $route->origin }} → {{ $route->destination }}
          </option>
        @endforeach
      </select>
      @error('route_id')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Departure Time</label>
      <input type="datetime-local" name="departure_at" class="form-control" value="{{ old('departure_at', $schedule->departure_at) }}" required>
      @error('departure_at')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Arrival Time</label>
      <input type="datetime-local" name="arrival_at" class="form-control" value="{{ old('arrival_at', $schedule->arrival_at) }}">
      @error('arrival_at')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Available Seats</label>
      <input type="number" name="available_seats" class="form-control" value="{{ old('available_seats', $schedule->available_seats) }}" min="1" required>
      @error('available_seats')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Price (Rp)</label>
      <input type="number" name="price" class="form-control" value="{{ old('price', $schedule->price) }}" step="0.01" min="0" required>
      @error('price')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="mb-3">
    <button type="submit" class="btn btn-primary">Update Schedule</button>
    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
