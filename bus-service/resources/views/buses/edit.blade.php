@extends('layouts.app')
@section('title','Edit Bus')
@section('content')
<h1>Edit Bus</h1>
<form method="POST" action="{{ route('buses.update',$bus) }}">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Plate Number</label>
    <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number',$bus->plate_number) }}">
    @error('plate_number')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$bus->name) }}">
    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Capacity</label>
    <input type="number" name="capacity" class="form-control" value="{{ old('capacity',$bus->capacity) }}">
    @error('capacity')<div class="text-danger">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label class="form-label">Type</label>
    <input type="text" name="type" class="form-control" value="{{ old('type',$bus->type) }}">
  </div>
  <div class="mb-3">
    <label class="form-label">Route ID (optional)</label>
    <input type="number" name="route_id" class="form-control" value="{{ old('route_id',$bus->route_id) }}">
  </div>
  <button class="btn btn-primary">Save</button>
</form>
@endsection
