@extends('layouts.app')
@section('title','Tickets')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Tickets</h1>
  <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>
</div>
<table class="table table-striped">
  <thead>
    <tr><th>ID</th><th>Ticket#</th><th>Schedule</th><th>Passenger</th><th>Contact</th><th>Seats</th><th>Total</th><th>Actions</th></tr>
  </thead>
  <tbody>
    @foreach($tickets as $t)
    <tr>
      <td>{{ $t->id }}</td>
      <td>{{ $t->ticket_number }}</td>
      <td>{{ $t->schedule_id }}</td>
      <td>{{ $t->passenger_name }}</td>
      <td>{{ $t->passenger_contact }}</td>
      <td>{{ $t->seat_count }}</td>
      <td>{{ $t->total_price }}</td>
      <td>
        <a href="{{ route('tickets.show',$t) }}" class="btn btn-sm btn-outline-primary">View</a>
        <a href="{{ route('tickets.edit',$t) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
        <form action="{{ route('tickets.destroy',$t) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
