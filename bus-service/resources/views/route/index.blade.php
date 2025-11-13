@extends('layouts.app')
@section('title','Routes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Routes</h1>
  <a href="{{ route('routes.create') }}" class="btn btn-primary">Create Route</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Code</th>
      <th>Origin</th>
      <th>Destination</th>
      <th>Stops</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($routes as $route)
    <tr>
      <td>{{ $route->id }}</td>
      <td>{{ $route->code }}</td>
      <td>{{ $route->origin }}</td>
      <td>{{ $route->destination }}</td>
      <td>
        @if($route->stops)
          {{ implode(', ', $route->stops) }}
        @else
          -
        @endif
      </td>
      <td>
        <a href="{{ route('routes.show', $route) }}" class="btn btn-sm btn-outline-primary">View</a>
        <a href="{{ route('routes.edit', $route) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
        <form action="{{ route('routes.destroy', $route) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this route?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
