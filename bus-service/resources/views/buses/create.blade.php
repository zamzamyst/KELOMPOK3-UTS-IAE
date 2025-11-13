@extends('layouts.app')
@section('title','Create Bus')
@section('content')
<h1>Create Bus</h1>
<form method="POST" action="{{ route('buses.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Plate Number</label>
    <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}">
    @error('plate_number')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Capacity</label>
    <input type="number" name="capacity" class="form-control" value="{{ old('capacity',30) }}">
    @error('capacity')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Type</label>
    <input type="text" name="type" class="form-control" value="{{ old('type') }}">
  </div>
  <div class="mb-3">
    <label class="form-label">Route ID (optional)</label>
    <input type="number" name="route_id" class="form-control" value="{{ old('route_id') }}">
  </div>
  <button class="btn btn-primary">Save</button>
</form>
@endsection
