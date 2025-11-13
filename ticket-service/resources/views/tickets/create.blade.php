@extends('layouts.app')
@section('title','Create Ticket')
@section('content')
<h1>Create Ticket</h1>
@if($errors->any())
  <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ route('tickets.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Passenger Name</label>
    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Schedule</label>
    <select name="schedule_id" class="form-select" required>
      <option value="">-- select schedule --</option>
      @foreach($schedules as $s)
        <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->departure_at }} → {{ $s->arrival_at }} (Seats: {{ $s->available_seats }})</option>
      @endforeach
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Seat Count</label>
    <input type="number" name="seat_count" value="1" min="1" class="form-control" required>
  </div>
  <button class="btn btn-primary">Create Ticket</button>
</form>
@endsection
