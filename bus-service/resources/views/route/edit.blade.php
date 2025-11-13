@extends('layouts.app')
@section('title','Edit Route')
@section('content')
<h1>Edit Route</h1>
<form method="POST" action="{{ route('routes.update', $route) }}">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Route Code</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $route->code) }}" required>
    @error('code')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Origin</label>
    <input type="text" name="origin" class="form-control" value="{{ old('origin', $route->origin) }}" required>
    @error('origin')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Destination</label>
    <input type="text" name="destination" class="form-control" value="{{ old('destination', $route->destination) }}" required>
    @error('destination')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Stops (comma-separated)</label>
    <textarea name="stops" class="form-control" rows="3">{{ old('stops', $route->stops ? implode(', ', $route->stops) : '') }}</textarea>
    <small class="form-text text-muted">Enter stop cities separated by commas, e.g: Jakarta, Bandung, Yogyakarta</small>
    @error('stops')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <button type="submit" class="btn btn-primary">Update Route</button>
    <a href="{{ route('routes.index') }}" class="btn btn-secondary">Cancel</a>
  </div>
</form>
@endsection
