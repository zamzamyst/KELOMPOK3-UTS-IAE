@extends('layouts.app')
@section('title','View Ticket')
@section('content')
<h1>Ticket #{{ $ticket->ticket_number }}</h1>
<dl class="row">
  <dt class="col-sm-3">ID</dt><dd class="col-sm-9">{{ $ticket->id }}</dd>
  <dt class="col-sm-3">Passenger</dt><dd class="col-sm-9">{{ $ticket->passenger_name }}</dd>
  <dt class="col-sm-3">Schedule</dt><dd class="col-sm-9">{{ $ticket->schedule_id }}</dd>
  <dt class="col-sm-3">Seats</dt><dd class="col-sm-9">{{ $ticket->seat_count }}</dd>
  <dt class="col-sm-3">Total</dt><dd class="col-sm-9">{{ $ticket->total_price }}</dd>
  <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $ticket->status }}</dd>
</dl>
<a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back</a>
@endsection
