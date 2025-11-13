@extends('layouts.app')
@section('title','Buses')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Buses</h1>
  <a href="{{ route('buses.create') }}" class="btn btn-primary">Create Bus</a>
</div>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>ID</th>
      <th>Plate</th>
      <th>Name</th>
      <th>Capacity</th>
      <th>Type</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($buses as $bus)
    <tr>
      <td>{{ $bus->id }}</td>
      <td>{{ $bus->plate_number }}</td>
      <td>{{ $bus->name }}</td>
      <td><span class="badge bg-info">{{ $bus->capacity }}</span></td>
      <td>{{ $bus->type ?? '-' }}</td>
      <td>
        <a href="{{ route('buses.show', $bus) }}" class="btn btn-sm btn-outline-primary">View</a>
        <a href="{{ route('buses.edit', $bus) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
        <form action="{{ route('buses.destroy', $bus) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this bus?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
