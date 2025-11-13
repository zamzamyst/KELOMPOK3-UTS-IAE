@extends('layouts.app')
@section('title','Schedules')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Schedules</h1>
  <a href="{{ route('schedules.create') }}" class="btn btn-primary">Create Schedule</a>
</div>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>ID</th>
      <th>Bus</th>
      <th>Route</th>
      <th>Departure</th>
      <th>Arrival</th>
      <th>Available Seats</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($schedules as $schedule)
    <tr>
      <td>{{ $schedule->id }}</td>
      <td>{{ $schedule->bus->name ?? 'N/A' }}</td>
      <td>{{ $schedule->route->code ?? 'N/A' }}</td>
      <td>{{ $schedule->departure_at }}</td>
      <td>{{ $schedule->arrival_at ?? '-' }}</td>
      <td>
        <span class="badge bg-info">{{ $schedule->available_seats }}</span>
      </td>
      <td>Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
      <td>
        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-outline-primary">View</a>
        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this schedule?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
